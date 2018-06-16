const BASE_URL = 'http://localhost/Unikee/public';
const getHeaderImage = async () => {
    let raw = await fetch(BASE_URL + '/api/header');

    let json = await raw.json();
    let img = document.createElement('img');
    img.src = BASE_URL + "/assets/" + json.header_image + '?' + new Date().getTime();

    let container = document.getElementById('header-image-preview');
    container.innerHTML = "";
    container.append(img);
};

const getIntroText = async () => {
    let raw = await fetch(BASE_URL + '/api/intro');
    let json = await raw.json();

    document.getElementById('intro-text').value = json.intro_text;
};

const getGallery = async () => {
    let raw = await fetch(BASE_URL + '/api/gallery');
    let json = await raw.json();
    let container = document.getElementById('gallery');
    container.innerHTML = "";
    json.forEach( image => {
        const wrapper = document.createElement('div');
        wrapper.classList.add('gallery-element');

        const img = document.createElement('img')
        img.src = BASE_URL + "/assets/gallery/" + image.path + '?' + new Date().getTime();

        const btn = document.createElement('button');
        btn.classList.add('close-btn');
        btn.dataset.id = image.id;
        btn.textContent = 'X';
        btn.addEventListener('click', deleteImage)

        wrapper.append(img);
        wrapper.append(btn);
        container.append(wrapper);
    });
};

const deleteImage = async e => {
    const options = {
        method: 'DELETE'
    }
    let request = await fetch(BASE_URL + `/api/gallery/${e.target.dataset.id}`, options);
    getGallery();
}

const handleHeaderSubmit = () => {
    const setHeader = async e => {
        e.preventDefault();
        const options = {
            method: 'POST',
            body: new FormData(form)
        }
        await fetch(BASE_URL + '/api/header', options);
        form.reset();
        getHeaderImage();
    }

    let form = document.getElementById('header-image-form');
    form.removeEventListener('submit', setHeader);
    form.addEventListener('submit', setHeader);
};

const handleIntroSubmit = async () => {
    const submitIntro = async e => {
        const intro_text = document.getElementById('intro-text').value;
        const json = JSON.stringify({ intro_text });
        const options = {
            method: 'POST',
            body: json,
            headers: {
                'Content-Type': 'application/json'
            }
        };
        let request = await fetch(BASE_URL + '/api/intro', options);
    }
    let submit = document.getElementById('intro-submit-btn');
    submit.removeEventListener('click', submitIntro);
    submit.addEventListener('click', submitIntro);
};

const handleGallerySubmit = () => {
    const addToGallery = async e => {
        e.preventDefault();
        let formData = new FormData(form);
        const options = {
            method: 'POST',
            body: formData
        }
        await fetch(BASE_URL + '/api/gallery', options);
        document.getElementById('gallery-input').value = "";
        form.reset();
        getGallery();
    }

    let form = document.getElementById('gallery-form');
    form.removeEventListener('submit', addToGallery);
    form.addEventListener('submit', addToGallery);
};

const changeListeners = () =>{
    let headerInput = document.getElementById('header-image-input');
    let headerLabel = document.getElementById('header-image-label');
    let headerSubmit = document.getElementById('header-image-submit');

    let galleryInput = document.getElementById('gallery-input');
    let galleryLabel = document.getElementById('gallery-label');
    let gallerySubmit = document.getElementById('gallery-submit');

    const displaySelectedFile = (e, submit, label) => {
        let val = e.target.value;
        if ( val.length > 0) {
            submit.removeAttribute('disabled');
            label.textContent = val.slice(val.lastIndexOf('\\') + 1 , val.lastIndexOf('.'));
        } else {
            submit.setAttribute('disabled', true);
        };

    }
    headerInput.addEventListener('change', e => displaySelectedFile(e, headerSubmit, headerLabel));
    galleryInput.addEventListener('change', e => displaySelectedFile(e, gallerySubmit, galleryLabel));
};

document.addEventListener('DOMContentLoaded', () => {
    getHeaderImage();
    getIntroText();
    getGallery();

    handleHeaderSubmit();
    handleIntroSubmit();
    handleGallerySubmit();

    changeListeners();
});
