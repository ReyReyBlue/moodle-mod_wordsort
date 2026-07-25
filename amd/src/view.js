export const init = (words, timingmode, timevalue) => {

    let currentIndex = 0;
    let correctAnswers = 0;
    let wrongAnswers = 0;
    let timerInterval;

    const wordElement = document.getElementById('wordsort-word');
    const leftButton = document.querySelector('.wordsort-choice-left');
    const rightButton = document.querySelector('.wordsort-choice-right');
    const timerElement = document.getElementById('wordsort-timer');

    //temp change to show timer value on start screen

    const mode = Number(timingmode);

    if (mode === 1) {
        timerElement.textContent = `Countdown: ${timevalue}`;
    } else if (mode === 2) {
        timerElement.textContent = `Stopwatch: 0`;
    }

function showWord() {

    if (!words.length) {
        return;
    }

    wordElement.textContent = words[currentIndex].word;
}

function checkAnswer(selectedSide) {

    if (currentIndex >= words.length) {
        return;
    }

    const correct = Number(words[currentIndex].correctside);

    if (selectedSide === correct) {
        correctAnswers++;
    } else {
        wrongAnswers++;
    }

    nextWord();
}

function nextWord() {

    currentIndex++;

    if (currentIndex >= words.length) {

        clearInterval(timerInterval);

        wordElement.textContent =
            `Finished! ${correctAnswers}/${words.length} correct`;

        return;
    }

        showWord();
    }

    const startButton = document.getElementById('wordsort-start');

    if (!startButton) {
        return;
    }

    startButton.addEventListener('click', () => {

        document.getElementById('wordsort-start-screen').style.display = 'none';
        document.getElementById('wordsort-activity-screen').style.display = 'block';

        if (mode === 1) {

            // Countdown will go here later.

        } else if (mode === 2) {

             let elapsed = 0;

             timerInterval = setInterval(() => {
                elapsed++;
                timerElement.textContent = `Stopwatch: ${elapsed}`;
             }, 1000);
        }
        showWord();
    });

leftButton.addEventListener('click', () => {
    checkAnswer(0);
});

rightButton.addEventListener('click', () => {
    checkAnswer(1);
});

};