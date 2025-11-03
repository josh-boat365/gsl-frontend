const note_takerForm = get(".note-taker-inputarea");
const note_takerInput = get(".note-taker-input");
const note_takerChat = get(".note-taker-chat");
const note_takerSide = get(".note-taker-side");

const BOT_MSGS = [
    "Hi, how are you?",
    "Ohh... I can't understand what you trying to say. Sorry!",
    "I like to play games... But I don't know how to play!",
    "Sorry if my answers are not relevant. :))",
    "I feel sleepy! :("
];

// Icons made by Freepik from www.flaticon.com
const REPLIER_IMG = "http://app.bluefstopup.test/theme/assets/images/users/16.jpg";
const SENDER_IMG = "http://app.bluefstopup.test/theme/assets/images/users/16.jpg";
const NOTE_REPLIER_NAME = "BOT";
const NOTE_SENDER_NAME = "Sajad";

note_takerForm.addEventListener("submit", event => {
    event.preventDefault();

    const noteText = note_takerInput.value;
    const msgSide = note_takerSide.value;
    if (!noteText) return;

    if(msgSide){
        appendMessageRight(NOTE_SENDER_NAME, SENDER_IMG, noteText);
    }else{
        appendMessageLeft(NOTE_REPLIER_NAME, REPLIER_IMG, noteText);
    }

    note_takerInput.value = "";
    botResponse();
});

function appendMessageLeft(name, img, text) {
    const msgHTML = `<div class="d-flex justify-content-start msg left-msg">
                                    <div class="img_cont_msg">
                                        <img src="${img}" class="rounded-circle user_img_msg" alt="img">
                                    </div>
                                    <div class="msg_cotainer">
                                        ${text}
                                        <span class="msg_time">${formatDate(new Date())}</span>
                                    </div>
                                </div>`;
    note_takerChat.insertAdjacentHTML("beforeend", msgHTML);
    note_takerChat.scrollTop += 500;
}

function appendMessageRight(name, img, text) {
    const msgHTML = `<div class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        ${text}
                                        <span class="msg_time_send">${formatDate(new Date())}</span>
                                    </div>
                                    <div class="img_cont_msg">
                                        <img src="${img}" class="rounded-circle user_img_msg" alt="img">
                                    </div>
                                </div>`;

    note_takerChat.insertAdjacentHTML("beforeend", msgHTML);
    note_takerChat.scrollTop += 500;
}

function botResponse() {
    const r = random(0, BOT_MSGS.length - 1);
    const noteText = BOT_MSGS[r];
    const delay = noteText.split(" ").length * 100;
    setTimeout(() => {
        appendMessageLeft(NOTE_REPLIER_NAME, REPLIER_IMG, noteText);
    }, delay);
}

// Utils
function get(selector, root = document) {
    return root.querySelector(selector);
}

function formatDate(date) {
    const h = "0" + date.getHours();
    const m = "0" + date.getMinutes();

    return `${h.slice(-2)}:${m.slice(-2)}`;
}

function random(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}









