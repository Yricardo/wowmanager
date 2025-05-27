// assets/controllers/conversation_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["form", "messageInput", "submitButton", "errorContainer", "successContainer", "messagesContainer"]
    static values = { 
        url: String,
        updateUrl: String,
        sendingText: { type: String, default: "Sending..." },
        defaultText: { type: String, default: "Send Message" },
        pollingInterval: { type: Number, default: 3000 },
        currentUserId: Number // Add this to make it explicit
    }

    connect() {
        this.originalButtonText = this.submitButtonTarget.textContent
        this.mostRecentMessageTimestamp = this.getMostRecentTimestamp()

        // Test polling with hardcoded backend data
        this.startPolling()
        
        // Stop polling when user leaves the page or controller disconnects
        this.handleVisibilityChange = this.handleVisibilityChange.bind(this)
        document.addEventListener('visibilitychange', this.handleVisibilityChange)
    }

    disconnect() {
        this.stopPolling()
        document.removeEventListener('visibilitychange', this.handleVisibilityChange)
    }

    handleVisibilityChange() {
        if (document.hidden) {
            this.stopPolling()
        } else {
            this.startPolling()
        }
    }

    startPolling() {
        if (this.pollingTimer) return // Avoid multiple timers
        
        this.pollingTimer = setInterval(() => {
            this.pollForNewMessages()
        }, this.pollingIntervalValue)
    }

    stopPolling() {
        if (this.pollingTimer) {
            clearInterval(this.pollingTimer)
            this.pollingTimer = null
        }
    }

    async pollForNewMessages() {
        if (!this.updateUrlValue) {
            console.warn('Update URL not configured for polling')
            return
        }

        try {
            const url = `${this.updateUrlValue}?mostRecentMessageDate=${this.mostRecentMessageTimestamp}`
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })

            const data = await response.json()

            if (response.ok && data.success && data.messages && data.messages.length > 0) {
                console.log('New messages received:', data.messages) // Debug log
                this.handleNewMessages(data.messages)
            } else if (!response.ok) {
                console.error('Polling error:', data.error || 'Failed to fetch messages')
            }
        } catch (error) {
            console.error('Polling network error:', error)
        }
    }

    handleNewMessages(messages) {

        messages.forEach((message, index) => {
            
            // Try multiple ways to extract the content
            const content = message.content || message.text || message.message || message.body
            console.log(`Message ${index} content:`, content)
            
            // Try multiple ways to extract sender ID
            let senderId = null
            if (message.sender) {
                senderId = message.sender.id || message.sender.getId || message.sender
            } else if (message.senderId) {
                senderId = message.senderId
            } else if (message.author) {
                senderId = message.author.id || message.author
            }
            console.log(`Message ${index} sender ID:`, senderId)
            
            // Try multiple ways to extract timestamp
            const timestamp = message.createdAt || message.created_at || message.timestamp || message.date
            console.log(`Message ${index} timestamp:`, timestamp)
            
            if (content && senderId && timestamp) {
                this.addMessageToDOM(content, senderId, timestamp)
                
                // Update the most recent timestamp
                const messageTime = new Date(timestamp).getTime() / 1000
                if (messageTime > this.mostRecentMessageTimestamp) {
                    this.mostRecentMessageTimestamp = messageTime
                }
            } else {
                console.error(`Message ${index} missing required data:`, {
                    content: !!content,
                    senderId: !!senderId,
                    timestamp: !!timestamp,
                    fullMessage: message
                })
            }
        })
    }

    // Helper method to extract sender ID from different formats
    extractSenderId(sender) {
        if (typeof sender === 'object' && sender !== null) {
            return sender.id || sender.getId || null
        }
        return sender
    }

    getMostRecentTimestamp() {
        // Get timestamp of the most recent message in the DOM, or current time if no messages
        const messages = this.messagesContainerTarget.querySelectorAll('[data-message-timestamp]')
        if (messages.length === 0) {
            return Math.floor(Date.now() / 1000)
        }
        
        let maxTimestamp = 0
        messages.forEach(msg => {
            const timestamp = parseInt(msg.dataset.messageTimestamp)
            if (timestamp > maxTimestamp) {
                maxTimestamp = timestamp
            }
        })
        
        return maxTimestamp || Math.floor(Date.now() / 1000)
    }

    async send(event) {
        event.preventDefault()
        
        const message = this.messageInputTarget.value.trim()
        
        if (!message) {
            this.showError("Please enter a message")
            return
        }
        // Disable form during submission
        this.setLoading(true)

        try {
            console.log('Making fetch request...')
            
            // Get CSRF token from meta tag (Symfony standard)
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
            
            // Add CSRF token if available
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken
            }
            
            const response = await fetch(this.urlValue, {
                method: 'POST',
                headers: headers,
                body: JSON.stringify({ message: message })
            })

            console.log('Response status:', response.status)
            console.log('Response ok:', response.ok)

            if (!response.ok) {
                console.error('Response not ok:', response.status, response.statusText)
                const errorText = await response.text()
                console.error('Error response body:', errorText)
                this.handleError(`Server error: ${response.status} ${response.statusText}`)
                return
            }

            const data = await response.json()
            console.log('Response data:', data)

            if (data.success) {
                this.handleSuccess(data, message)
            } else {
                this.handleError(data.error || 'Failed to send message')
            }
        } catch (error) {
            console.error('Fetch error details:', error)
            console.error('Error name:', error.name)
            console.error('Error message:', error.message)
            this.handleError('Network error: ' + error.message)
        } finally {
            this.setLoading(false)
        }
    }

    handleSuccess(data, content) {
        this.messageInputTarget.value = '' // Clear the input
        
        // Add the sent message to DOM immediately
        const currentUserId = this.getCurrentUserId()
        console.log('Sending message - current user ID:', currentUserId) // Debug
        
        if (!currentUserId) {
            console.error('Current user ID is null/undefined!')
            return
        }
        
        const now = new Date().toISOString()
        this.addMessageToDOM(content, currentUserId, now)
        
        // Update timestamp to prevent polling from fetching this message again
        const messageTime = new Date(now).getTime() / 1000
        if (messageTime > this.mostRecentMessageTimestamp) {
            this.mostRecentMessageTimestamp = messageTime
        }

        // Optional: dispatch custom event for other controllers to listen
        this.dispatch("sent", { 
            detail: { message: data.message } 
        })
    }

    handleError(errorMessage) {
        console.error('Message error:', errorMessage)
        // You can implement UI error display here if needed
    }

    setLoading(isLoading) {
        this.submitButtonTarget.disabled = isLoading
        this.messageInputTarget.disabled = isLoading
        
        if (isLoading) {
            this.submitButtonTarget.textContent = this.sendingTextValue
            this.submitButtonTarget.classList.add('opacity-50')
        } else {
            this.submitButtonTarget.textContent = this.originalButtonText || this.defaultTextValue
            this.submitButtonTarget.classList.remove('opacity-50')
        }
    }

    // Handle Enter key to submit
    handleKeydown(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault()
            this.send(event)
        }
    }

    // Enhanced addMessageToDOM to handle both sent and received messages correctly
    addMessageToDOM(messageText, senderId, createdAt) {
        if (!this.hasMessagesContainerTarget) {
            console.warn('Messages container not found')
            return
        }

        // Safety checks
        if (!messageText) {
            console.error('Message text is undefined or empty:', messageText)
            return
        }

        if (!senderId) {
            console.error('Sender ID is undefined:', senderId)
            return
        }

        if (!createdAt) {
            console.error('Created at is undefined:', createdAt)
            return
        }

        const messageTime = new Date(createdAt)
        const timestamp = Math.floor(messageTime.getTime() / 1000)
        
        // Check if message with this timestamp already exists (prevent duplicates)
        const existingMessage = this.messagesContainerTarget.querySelector(`[data-message-timestamp="${timestamp}"]`)
        if (existingMessage) {
            console.log('Message already exists, skipping duplicate')
            return
        }

        // Convert both to numbers for proper comparison
        const currentUserId = this.getCurrentUserId()
        const messageSenderId = parseInt(senderId)
        const isCurrentUser = messageSenderId === currentUserId
        
        console.log('Message alignment:', {
            currentUserId,
            messageSenderId,
            isCurrentUser,
            messageText: messageText ? messageText.substring(0, 20) + '...' : 'undefined'
        }) // Debug log
        
        const timeString = messageTime.toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false 
        })
        
        const messageHTML = `
            <div data-message-timestamp="${timestamp}" style="display: flex; flex-direction: column; align-items: ${isCurrentUser ? 'flex-end' : 'flex-start'};">
                <div style="max-width: 65%; padding: 0.7rem 1.1rem; border-radius: 18px; background: ${isCurrentUser ? '#4f8cff' : '#393e46'}; color: #fff; font-size: 1.05em; box-shadow: 0 1px 4px rgba(0,0,0,0.07);">
                    ${this.escapeHtml(messageText)}
                </div>
                <span style="font-size: 0.8em; color: #888; margin-top: 0.2rem;">
                    ${timeString}
                </span>
            </div>
        `
        
        // Add to messages container
        this.messagesContainerTarget.insertAdjacentHTML('beforeend', messageHTML)
        
        // Scroll to bottom
        this.messagesContainerTarget.scrollTop = this.messagesContainerTarget.scrollHeight
        
        // Update most recent timestamp
        if (timestamp > this.mostRecentMessageTimestamp) {
            this.mostRecentMessageTimestamp = timestamp
        }
    }

    // Get current user ID with fallback methods
    getCurrentUserId() {
        return this.currentUserIdValue || 
               parseInt(this.element.dataset.conversationCurrentUserIdValue) || 
               parseInt(this.element.getAttribute('data-conversation-current-user-id-value')) ||
               null
    }

    // Helper method to escape HTML to prevent XSS
    escapeHtml(text) {
        const div = document.createElement('div')
        div.textContent = text
        return div.innerHTML
    }

    showError(message) {
        console.error('Validation error:', message)
        // You can implement UI error display here
    }
}