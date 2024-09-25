export default {
    beforeMount(el) {
        el.style.position = 'relative';
        el.style.overflow = 'hidden';
        el.addEventListener('click', function (event) {
            // Tạo phần tử ripple
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            el.appendChild(ripple);

            // Tính toán kích thước và vị trí của ripple
            const rect = el.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${event.clientX - rect.left - size / 2}px`;
            ripple.style.top = `${event.clientY - rect.top - size / 2}px`;

            // Xóa ripple sau khi hiệu ứng kết thúc
            ripple.addEventListener('animationend', () => {
                ripple.remove();
            });
        });
    }
};
