document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.wordsort-details-button').forEach(button => {

        button.addEventListener('click', () => {

            const target = document.getElementById(
                button.dataset.target
            );

            if (target.style.display === 'table-row') {

                target.style.display = 'none';
                button.innerHTML = button.dataset.collapsed;

            } else {

                target.style.display = 'table-row';
                button.innerHTML = button.dataset.expanded;
            }

        });

    });

});