export const init = (words) => {
    let currentIndex = 0;
    let correctAnswers = 0;
    let wrongAnswers = 0;

const wordElement = document.getElementById('wordsort-word');
const leftButton = document.querySelector('.wordsort-choice-left');
const rightButton = document.querySelector('.wordsort-choice-right');

function showWord() {

    if (!words.length) {
        return;
    }

    wordElement.textContent = words[currentIndex].word;
}

function checkAnswer(selectedSide) {

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

    showWord();

});

leftButton.addEventListener('click', () => {
    checkAnswer(0);
});

rightButton.addEventListener('click', () => {
    checkAnswer(1);
});

};