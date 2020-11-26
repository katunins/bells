<link rel="stylesheet" href="{{ asset('css/supermodal.css') }}">
<input type="hidden" id="modal-temporary-data" value="">

<div class="super-modal hide">
    <div class="modal-block">
        <div class="close-modal-button hide">
            <button onclick="turnOFFSuperModal()">×</button>
        </div>
        <div class="modal-gallery-block hide">
            <button class="arrow">
                <img src="images/arrow-right.svg" alt="" style="transform: rotate(180deg)">
            </button>
            <div class="gallery-image"></div>
            <button class="arrow">
                <img src="images/arrow-right.svg" alt="">
            </button>
        </div>
        <div class="modal-img-block hide"></div>
        <div class="super-modal-message"></div>
        <div class="modal-cssload-wrap hide">
            <div class="cssload-cssload-spinner"></div>
        </div>
        <div class="super-model-buttons">
            <button class="hide" id="ok-modal-button">ok</button>
            <button class="hide" id="cancel-modal-button">Отменить</button>
        </div>

    </div>
</div>
<script>
    let okButton = document.getElementById('ok-modal-button')
    let cancelButton = document.getElementById('cancel-modal-button')


    function setOkModalButton(okButtonCallback = turnOFFSuperModal, name = 'ok') {

        okButton.innerHTML = name
        // okButton.addEventListener('click', okButtonCallback)
        okButton.onclick = okButtonCallback
        okButton.classList.remove('hide')

    }


    function setCancelModalButton(cancelButtonCallback = turnOFFSuperModal, name = 'Отмена') {

        cancelButton.innerHTML = name
        // cancelButton.addEventListener('click', cancelButtonCallback)
        cancelButton.onclick = cancelButtonCallback
        cancelButton.classList.remove('hide')

    }

    function turnOFFSuperModal() {

        document.querySelector('.super-modal').classList.add('hide')
        document.querySelector('.modal-img-block').style = ''
        document.querySelector('.modal-img-block').classList.add('hide')
        document.querySelector('.modal-gallery-block').classList.add('hide')
        document.querySelector('.gallery-image').backgroundImage =''
        
        document.querySelector('.super-modal-message').innerHTML = ''
        document.querySelector('.super-modal-message').classList.add('hide')
        document.removeEventListener('keyup', escKey);
        document.querySelector('.modal-block').style = ''
        document.querySelector('.modal-cssload-wrap').classList.add('hide');
        document.getElementById('modal-temporary-data').value = '';
        document.querySelector('.close-modal-button').classList.add('hide');

        okButton.classList.add('hide')
        cancelButton.classList.add('hide')

    }

    function escKey(key) {
        if (key.key === 'Escape') turnOFFSuperModal();
    }

    function turnONmodalLoader() {
        document.querySelector('.modal-cssload-wrap').classList.remove('hide');
    }

    function turnONmodalMessage(message) {
        document.querySelector('.super-modal-message').classList.remove('hide');
        document.querySelector('.super-modal-message').innerHTML = message
    }

    function turnONmodalImage(url) {
        document.querySelector('.modal-img-block').classList.remove('hide');
        document.querySelector('.modal-img-block').style.backgroundImage = 'url(' + url + ')'
    }

    function turnONmodalGallery (imageArrays) {
        document.querySelector('.modal-gallery-block').classList.remove('hide')
        let divImages=''
        imageArrays.forEach(item => {
            divImages += '<div style="background-image: url('+item+')"></div>'
        });
        document.querySelector('.gallery-image').innerHTML = divImages
    }

    function turnONmodal(margin, closeButton = true) {
        
        document.querySelector('.super-modal').classList.remove('hide');
        
        let shiftUp = (window.innerHeight - document.querySelector('.modal-block').clientHeight)/2
        if (!document.querySelector('.modal-img-block').classList.contains('hide')) {
            shiftUp -=shiftUp/2
        }
         
        if (shiftUp > 0) ; else shiftUp = 40; //немного сместим большое окно от верхней шапки
        document.querySelector('.modal-block').style.marginBottom = 40
        document.querySelector('.modal-block').style.top = shiftUp

        if (closeButton) {
            document.querySelector('.close-modal-button').classList.remove('hide');
            document.addEventListener('keyup', escKey, {
                once: true
            });
        }
    }

</script>
