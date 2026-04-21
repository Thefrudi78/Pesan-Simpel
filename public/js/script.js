const chatForm = document.getElementById('chatForm');
const msgInput = document.getElementById('msgInput');
const messageDisplay = document.getElementById('messageDisplay');

chatForm.addEventListener('submit', (e) => {
    e.preventDefault();

    const messageText = msgInput.value.trim();

    if (messageText !== "") {
        // Create the message element
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', 'sent');

        // Add content
        const now = new Date();
        const timeString = now.getHours() + ":" + now.getMinutes().toString().padStart(2, '0');
        
        messageDiv.innerHTML = `
            <p>${messageText}</p>
            <span class="time">${timeString}</span>
        `;

        // Append to display
        messageDisplay.appendChild(messageDiv);

        // Clear input and scroll to bottom
        msgInput.value = '';
        messageDisplay.scrollTop = messageDisplay.scrollHeight;
    }
});