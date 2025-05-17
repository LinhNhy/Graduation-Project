const list = document.querySelector('.infor-list');
const dotsContainer = document.querySelector('.dots');
const sliderWrapper = list.parentElement;

// Tạo nút Previous & Next
const prevBtn = document.createElement('button');
const nextBtn = document.createElement('button');
prevBtn.classList.add('nav-btn', 'prev');
nextBtn.classList.add('nav-btn', 'next');
prevBtn.innerHTML = '◀';
nextBtn.innerHTML = '▶';

// Thêm nút vào slider
sliderWrapper.appendChild(prevBtn);
sliderWrapper.appendChild(nextBtn);

let index = 0;
let itemsPerPage = window.innerWidth <= 400 ? 1 : 3;
let totalPages = Math.ceil(list.children.length / itemsPerPage);

function updateDots() {
    dotsContainer.innerHTML = '';
    for (let i = 0; i < totalPages; i++) {
        const dot = document.createElement('span');
        dot.classList.add('dot');
        if (i === index) dot.classList.add('active');
        dot.addEventListener('click', () => moveToSlide(i));
        dotsContainer.appendChild(dot);
    }
    updateButtons();
}

function moveToSlide(i) {
    index = Math.max(0, Math.min(i, totalPages - 1));
    list.style.transform = `translateX(-${index * 100}%)`;
    updateDots();
}

function updateButtons() {
    prevBtn.style.display = index === 0 ? 'none' : 'flex';
    nextBtn.style.display = index === totalPages - 1 ? 'none' : 'flex';
}

// Sự kiện cho nút Previous
prevBtn.addEventListener('click', () => moveToSlide(index - 1));

// Sự kiện cho nút Next
nextBtn.addEventListener('click', () => moveToSlide(index + 1));

// Cập nhật khi resize màn hình
window.addEventListener('resize', () => {
    itemsPerPage = window.innerWidth <= 400 ? 1 : 3;
    totalPages = Math.ceil(list.children.length / itemsPerPage);
    updateDots();
});

// Khởi tạo dots và buttons ban đầu
updateDots();
