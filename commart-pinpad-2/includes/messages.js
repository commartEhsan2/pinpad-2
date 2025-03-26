document.addEventListener('DOMContentLoaded', function() {
    let inactivityTimeout;
    const phoneNumberField = document.getElementById('number-display');
    const buttons = document.querySelectorAll('.commart-login-popup .num');
    const clearButton = document.querySelector('.commart-login-popup .clear');
    const swapButton = document.getElementById('swap-button');
    const messages = [
        { id: 1, text: "ورود", timeout: 1000, effectIn: "animate__fadeInUp", effectOut: "animate__fadeOutDown" },
        { id: 2, text: "لطفا شماره موبایل خود را وارد نمایید.", timeout: 3000, effectIn: "animate__fadeInUp", effectOut: "animate__fadeOutDown" },
        { id: 3, text: "شماره موبایل صحیح نیست.", effectIn: "animate__bounceIn", effectOut: "animate__fadeOutDown" },
        { id: 4, text: "ثبت نام", timeout: 1000, effectIn: "animate__fadeInUp", effectOut: "animate__fadeOutDown" },
        { id: 5, text: "در حال بررسی ...", effectIn: "animate__fadeIn", effectOut: "animate__fadeOut" },
        { id: 6, text: "ورود با موفقیت انجام شد.", effectIn: "animate__fadeInUp" },
        { id: 7, text: "ثبت نام با موفقیت انجام شد.", effectIn: "animate__fadeInUp" }
    ];

    function showMessage(index) {
        if (index < messages.length) {
            const msg = messages[index];
            const messageElement = document.createElement('div');
            messageElement.id = `message-${msg.id}`;
            messageElement.className = `message ${msg.effectIn}`;
            messageElement.textContent = msg.text;
            document.body.appendChild(messageElement);

            setTimeout(() => {
                messageElement.classList.replace(msg.effectIn, msg.effectOut);
                setTimeout(() => {
                    messageElement.remove();
                    if (msg.timeout) showMessage(index + 1);
                }, 1000);
            }, msg.timeout || 2000);
        }
    }

    function handleInput() {
        clearTimeout(inactivityTimeout);
        const messageElement = document.getElementById('message-2');
        if (messageElement) {
            messageElement.classList.replace('animate__fadeInUp', 'animate__fadeOutDown');
            setTimeout(() => messageElement.remove(), 1000);
        }

        inactivityTimeout = setTimeout(() => showMessage(1), 3000);
    }

    buttons.forEach(button => button.addEventListener('click', handleInput));
    clearButton.addEventListener('click', handleInput);
    swapButton.addEventListener('click', handleInput);

    showMessage(0);
});