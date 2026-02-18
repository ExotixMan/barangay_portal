document.addEventListener('DOMContentLoaded', function() {
    const faqButtons = document.querySelectorAll('.faq-question');
    console.log('FAQ buttons found:', faqButtons.length);

    faqButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            console.log('FAQ clicked!');
            
            const faqItem = this.closest('.faq-item');
            const answer = faqItem.querySelector('.faq-answer');
            const isActive = this.classList.contains('active');
            
            // Close all FAQs
            document.querySelectorAll('.faq-question').forEach(function(q) {
                q.classList.remove('active');
            });
            document.querySelectorAll('.faq-answer').forEach(function(a) {
                a.classList.remove('active');
            });
            
            // Toggle current
            if (!isActive) {
                this.classList.add('active');
                answer.classList.add('active');
            }
        });
    });
});