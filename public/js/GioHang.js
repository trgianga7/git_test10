document.querySelectorAll('.cart-form').forEach(form => {

    const input = form.querySelector('.qty-input');
    const giam = form.querySelector('.btn-giam');
    const tang = form.querySelector('.btn-tang');

    giam.addEventListener('click', () => {
        let value = parseInt(input.value);
        if (value > 1) {
            input.value = value - 1;
            form.requestSubmit(); 
        }
    });

    tang.addEventListener('click', () => {
        input.value = parseInt(input.value) + 1;
        form.requestSubmit(); 
    });

    input.addEventListener('change', () => {
        if (input.value < 1) input.value = 1;
        form.requestSubmit();
    });

});