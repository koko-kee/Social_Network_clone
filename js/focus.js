
const send = document.querySelector(".send-button");
const commentButtons = document.querySelectorAll('.comment-button');


function addFocusClass(element) {
    element.parentNode.classList.add('focus');
}
  
function removeFocusClass(element) {
    element.parentNode.classList.remove('focus');
}
  
function autoResize(textarea) {
  textarea.style.height = '100px'; 
  textarea.style.height = textarea.scrollHeight + 'px';
  textarea.classList.add('auto-resize');
}
  
function heightTextarea(textarea) {
  textarea.style.height = '100px';
  textarea.style.resize = 'none'; 
  send.style.display = "block";
}


commentButtons.forEach((button) => {
  button.addEventListener('click', () => {
    const textarea = button.parentNode.parentNode.querySelector('.comment-textarea');
    textarea.style.height = '100px';
  });
});



  
  