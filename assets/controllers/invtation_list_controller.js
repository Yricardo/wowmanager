import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["searchInput", "statusSelect", "invitationItem"]
    static values = { 
        baseUrl: String,
        copySuccessText: { type: String, default: "🔗 Invitation link copied to clipboard!" },
        resendSuccessText: { type: String, default: "✅ Invitation resent successfully!" },
        cancelSuccessText: { type: String, default: "🚫 Invitation cancelled successfully!" },
        renewSuccessText: { type: String, default: "✨ Invitation renewed successfully!" }
    }

    connect() {
        console.log("Invitation list controller connected")
        this.setupFilterListeners()
    }

    setupFilterListeners() {
        if (this.hasSearchInputTarget) {
            this.searchInputTarget.addEventListener('input', this.debounce(this.filterInvitations.bind(this), 300))
        }
        
        if (this.hasStatusSelectTarget) {
            this.statusSelectTarget.addEventListener('change', this.filterInvitations.bind(this))
        }
    }

    // Copy invitation link to clipboard
    async copyLink(event) {
        event.preventDefault()
        
        const button = event.currentTarget
        const token = button.dataset.token
        
        if (!token) {
            this.showError("No invitation token found")
            return
        }

        try {
            const link = `${window.location.origin}/register/member/${token}`
            await navigator.clipboard.writeText(link)
            
            this.showSuccess(this.copySuccessTextValue)
            this.flashButton(button, "✅ Copied!", "📋 Copy Link")
            
        } catch (error) {
            console.error('Failed to copy link:', error)
            this.showError("Failed to copy link to clipboard")
        }
    }

    // Resend invitation
    async resendInvitation(event) {
        event.preventDefault()
        
        const button = event.currentTarget
        const invitationId = button.dataset.invitationId
        
        if (!invitationId) {
            this.showError("No invitation ID found")
            return
        }

        if (!confirm('📤 Resend this invitation?')) {
            return
        }

        this.setButtonLoading(button, true, "📤 Sending...")

        try {
            const response = await this.makeRequest(`/member/invitation/${invitationId}/resend`, 'POST')
            
            if (response.success) {
                this.showSuccess(this.resendSuccessTextValue)
                this.refreshPage()
            } else {
                this.showError(response.error || 'Failed to resend invitation')
            }
        } catch (error) {
            console.error('Resend error:', error)
            this.showError('Network error: Failed to resend invitation')
        } finally {
            this.setButtonLoading(button, false, "📤 Resend")
        }
    }

    // Cancel invitation
    async cancelInvitation(event) {
        event.preventDefault()
        
        const button = event.currentTarget
        const invitationId = button.dataset.invitationId
        
        if (!invitationId) {
            this.showError("No invitation ID found")
            return
        }

        if (!confirm('❌ Cancel this invitation? This action cannot be undone.')) {
            return
        }

        this.setButtonLoading(button, true, "❌ Cancelling...")

        try {
            const response = await this.makeRequest(`/member/invitation/${invitationId}/cancel`, 'POST')
            
            if (response.success) {
                this.showSuccess(this.cancelSuccessTextValue)
                this.refreshPage()
            } else {
                this.showError(response.error || 'Failed to cancel invitation')
            }
        } catch (error) {
            console.error('Cancel error:', error)
            this.showError('Network error: Failed to cancel invitation')
        } finally {
            this.setButtonLoading(button, false, "❌ Cancel")
        }
    }

    // Renew invitation
    async renewInvitation(event) {
        event.preventDefault()
        
        const button = event.currentTarget
        const invitationId = button.dataset.invitationId
        
        if (!invitationId) {
            this.showError("No invitation ID found")
            return
        }

        if (!confirm('🔄 Renew this invitation for another 7 days?')) {
            return
        }

        this.setButtonLoading(button, true, "🔄 Renewing...")

        try {
            const response = await this.makeRequest(`/member/invitation/${invitationId}/renew`, 'POST')
            
            if (response.success) {
                this.showSuccess(this.renewSuccessTextValue)
                this.refreshPage()
            } else {
                this.showError(response.error || 'Failed to renew invitation')
            }
        } catch (error) {
            console.error('Renew error:', error)
            this.showError('Network error: Failed to renew invitation')
        } finally {
            this.setButtonLoading(button, false, "🔄 Renew")
        }
    }

    // Filter invitations by search and status
    filterInvitations() {
        const searchTerm = this.hasSearchInputTarget ? this.searchInputTarget.value.toLowerCase() : ''
        const statusFilter = this.hasStatusSelectTarget ? this.statusSelectTarget.value : ''

        this.invitationItemTargets.forEach(item => {
            const email = item.dataset.email?.toLowerCase() || ''
            const username = item.dataset.username?.toLowerCase() || ''
            const status = item.dataset.status || ''

            const matchesSearch = !searchTerm || 
                email.includes(searchTerm) || 
                username.includes(searchTerm)
            
            const matchesStatus = !statusFilter || status === statusFilter

            if (matchesSearch && matchesStatus) {
                item.style.display = 'block'
            } else {
                item.style.display = 'none'
            }
        })

        this.updateResultsCount()
    }

    // Update results count display
    updateResultsCount() {
        const visibleItems = this.invitationItemTargets.filter(item => 
            item.style.display !== 'none'
        ).length
        
        // Dispatch event with count for other controllers to listen
        this.dispatch("filtered", { 
            detail: { 
                total: this.invitationItemTargets.length,
                visible: visibleItems 
            } 
        })
    }

    // Helper method to make AJAX requests
    async makeRequest(url, method = 'GET', data = null) {
        const headers = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }

        // Get CSRF token from meta tag or form
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken
        }

        const options = {
            method,
            headers
        }

        if (data) {
            headers['Content-Type'] = 'application/json'
            options.body = JSON.stringify(data)
        }

        const response = await fetch(url, options)
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`)
        }

        return await response.json()
    }

    // Set button loading state
    setButtonLoading(button, isLoading, loadingText) {
        if (isLoading) {
            button.dataset.originalText = button.textContent
            button.textContent = loadingText
            button.disabled = true
            button.classList.add('opacity-50')
        } else {
            button.textContent = button.dataset.originalText || button.textContent
            button.disabled = false
            button.classList.remove('opacity-50')
        }
    }

    // Flash button with temporary text
    flashButton(button, flashText, originalText, duration = 2000) {
        const original = button.textContent
        button.textContent = flashText
        button.classList.add('flash-success')
        
        setTimeout(() => {
            button.textContent = originalText || original
            button.classList.remove('flash-success')
        }, duration)
    }

    // Show success message
    showSuccess(message) {
        console.log('Success:', message)
        // You can implement toast notifications here
        // For now, using alert as fallback
        if (typeof window.showToast === 'function') {
            window.showToast(message, 'success')
        } else {
            alert(message)
        }
    }

    // Show error message
    showError(message) {
        console.error('Error:', message)
        // You can implement toast notifications here
        // For now, using alert as fallback
        if (typeof window.showToast === 'function') {
            window.showToast(message, 'error')
        } else {
            alert(message)
        }
    }

    // Refresh the page
    refreshPage() {
        // You could implement partial refresh here instead
        setTimeout(() => {
            window.location.reload()
        }, 1000)
    }

    // Debounce utility for search input
    debounce(func, wait) {
        let timeout
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout)
                func(...args)
            }
            clearTimeout(timeout)
            timeout = setTimeout(later, wait)
        }
    }

    // Handle filter button click
    applyFilters(event) {
        event.preventDefault()
        this.filterInvitations()
    }

    // Clear all filters
    clearFilters(event) {
        event.preventDefault()
        
        if (this.hasSearchInputTarget) {
            this.searchInputTarget.value = ''
        }
        
        if (this.hasStatusSelectTarget) {
            this.statusSelectTarget.value = ''
        }
        
        this.filterInvitations()
    }
}