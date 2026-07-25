export const init = (words, timingmode, timevalue) => {

    let currentIndex = 0;
    let correctAnswers = 0;
    let wrongAnswers = 0;
    let timerInterval;
    let elapsed = 0;

    const startScreen = document.getElementById('wordsort-start-screen');
    const activityScreen = document.getElementById('wordsort-activity-screen');
    const resultsScreen = document.getElementById('wordsort-results-screen');
    const resultScore = document.getElementById('wordsort-result-score');
    const resultTime = document.getElementById('wordsort-result-time');
    const resultButtons = document.getElementById('wordsort-result-buttons');

    const wordElement = document.getElementById('wordsort-word');
    const timerElement = document.getElementById('wordsort-timer');

    const leftButton = document.querySelector('.wordsort-choice-left');
    const rightButton = document.querySelector('.wordsort-choice-right');

    const startButton = document.getElementById('wordsort-start');
    
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
    finishGame();
    return;
}
        showWord();
    }

function finishGame() {

    clearInterval(timerInterval);

    activityScreen.style.display = 'none';
    resultsScreen.style.display = 'block';

    resultScore.textContent =
    `Score: ${correctAnswers}/${words.length}`;

    resultTime.textContent =
    `Time: ${elapsed} seconds`;
}
    
    if (!startButton) {
        return;
    }

    startButton.addEventListener('click', () => {

        startScreen.style.display = 'none';
        activityScreen.style.display = 'block';

        if (mode === 1) {

            // Countdown will go here later.

} else if (mode === 2) {

    clearInterval(timerInterval);

    elapsed = 0;
    timerElement.textContent = 'Stopwatch: 0';

    timerInterval = setInterval(() => {
        elapsed++;
        timerElement.textContent = `Stopwatch: ${elapsed}`;
    }, 1000);
}
        showWord();
    });

if (leftButton) {
    leftButton.addEventListener('click', () => {
        checkAnswer(0);
    });
}

if (rightButton) {
    rightButton.addEventListener('click', () => {
        checkAnswer(1);
    });
}

};