export const init = () => {

    const startButton = document.getElementById('wordsort-start');

    if (!startButton) {
        return;
    }

    startButton.addEventListener('click', () => {

        document.getElementById('wordsort-start-screen').style.display = 'none';

        document.getElementById('wordsort-activity-screen').style.display = 'block';

    });

};