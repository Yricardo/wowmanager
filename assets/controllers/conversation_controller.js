// assets/controllers/message_sender_controller.js
import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = ["form", "messageInput", "submitButton", "errorContainer", "successContainer", "messagesContainer"]
    static values = { 
        url: String,
        sendingText: { type: String, default: "Sending..." },
        defaultText: { type: String, default: "Send Message" }
    }

    connect() {
        this.originalButtonText = this.submitButtonTarget.textContent
    }

    async send(event) {
        console.log('hacking nasa plz wait lol');
        
        event.preventDefault()
        
        const message = this.messageInputTarget.value.trim()
        console.log(message);
        
        if (!message) {
            this.showError("Please enter a message")
            return
        }

        // Disable form during submission
        this.setLoading(true)

        try {
            const response = await fetch(this.urlValue, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message })
            })

            const data = await response.json()

            if (response.ok && data.success) {
                this.handleSuccess(data, message)
            } else {
                this.handleError(data.error || 'Failed to send message')
            }
        } catch (error) {
            this.handleError('Network error. Please try again.')
        } finally {
            this.setLoading(false)
        }
    }

    handleSuccess(data, content) {
        this.messageInputTarget.value = '' // Clear the input
        //add html message occurence
        this.addMessageToDOM(content)

        // Optional: dispatch custom event for other controllers to listen
        this.dispatch("sent", { 
            detail: { message: data.message } 
        })
    }

    handleError(errorMessage) {
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

    // Optional: Handle Enter key to submit
    handleKeydown(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault()
            this.send(event)
        }
    }

    // Add new message to the DOM
    addMessageToDOM(messageText) {
        if (!this.hasMessagesContainerTarget) {
            console.warn('Messages container not found')
            return
        }

        // Create message HTML string
        const currentTime = new Date().toLocaleTimeString('en-US', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false 
        })
        
        const messageHTML = `
            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                <div style="max-width: 65%; padding: 0.7rem 1.1rem; border-radius: 18px; background: #4f8cff; color: #fff; font-size: 1.05em; box-shadow: 0 1px 4px rgba(0,0,0,0.07);">
                    ${messageText}
                </div>
                <span style="font-size: 0.8em; color: #888; margin-top: 0.2rem;">
                    ${currentTime}
                </span>
            </div>
        `
        
        // Add to messages container
        this.messagesContainerTarget.insertAdjacentHTML('beforeend', messageHTML)
        
        // Scroll to bottom
        this.messagesContainerTarget.scrollTop = this.messagesContainerTarget.scrollHeight
    }    
}