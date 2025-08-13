import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

function initApp() {
    // Инициализация Swiper
    window.Swiper = Swiper;

    // Инициализация hero-слайдера
    function initializeHeroSlider() {
        console.log('Инициализация hero-slider');
        let currentSlide = 1; // Начинаем со второго слайда (индекс 1)
        const slider = document.getElementById('hero-slider');
        const slides = document.querySelectorAll('#hero-slider > div');
        const indicators = document.querySelectorAll('.hero-indicator');
        const sliderWrap = document.getElementById('slider-wrap');
        const prevButton = document.querySelector('.hero-prev');
        const nextButton = document.querySelector('.hero-next');
        const gap = 20;

        if (!slider || !sliderWrap || slides.length === 0) {
            console.warn('Слайдер, обертка или слайды не найдены');
            return;
        }

        function getSlideWidth() {
            return slider.parentElement.offsetWidth;
        }

        function updateSlider(applyTransition = true) {
            const slideWidth = getSlideWidth();
            slider.style.transition = applyTransition ? 'transform 0.5s ease-in-out' : 'none';
            slider.style.transform = `translateX(-${currentSlide * (slideWidth + gap)}px)`;

            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('bg-green-500', index === currentSlide);
                indicator.classList.toggle('bg-white/50', index !== currentSlide);
            });
            console.log(`Слайдер обновлен, текущий слайд: ${currentSlide}`);
        }

        // Инициализация без анимации
        updateSlider(false);

        // Включение видимости и анимации
        requestAnimationFrame(() => {
            slider.classList.remove('opacity-0', 'invisible');
            sliderWrap.classList.remove('opacity-0', 'invisible');
            slider.style.transition = 'transform 0.5s ease-in-out';
            updateSlider(true);
        });

        // Функции навигации
        function moveSlide(direction) {
            if (slides.length === 0) return;
            currentSlide = (currentSlide + direction + slides.length) % slides.length;
            updateSlider();
            console.log(`Переключение слайда, направление: ${direction}, текущий слайд: ${currentSlide}`);
        }

        function goToSlide(index) {
            if (slides.length === 0) return;
            currentSlide = parseInt(index, 10);
            updateSlider();
            console.log(`Переход к слайду: ${currentSlide}`);
        }

        // Привязка обработчиков событий
        function attachEventListeners() {
            if (prevButton) {
                prevButton.removeEventListener('click', () => moveSlide(-1));
                prevButton.addEventListener('click', () => moveSlide(-1));
                console.log('Обработчик для prevButton привязан');
            } else {
                console.warn('Кнопка prevButton не найдена');
            }
            if (nextButton) {
                nextButton.removeEventListener('click', () => moveSlide(1));
                nextButton.addEventListener('click', () => moveSlide(1));
                console.log('Обработчик для nextButton привязан');
            } else {
                console.warn('Кнопка nextButton не найдена');
            }
            indicators.forEach((indicator, index) => {
                indicator.removeEventListener('click', () => goToSlide(index));
                indicator.addEventListener('click', () => goToSlide(indicator.getAttribute('data-slide')));
                console.log(`Обработчик для индикатора ${index} привязан`);
            });
        }

        // Первоначальная привязка обработчиков
        attachEventListeners();

        // Обновление при изменении размера окна
        window.addEventListener('resize', () => {
            if (slides.length > 0) {
                updateSlider(false);
                console.log('Слайдер обновлен при изменении размера окна');
            }
        });
    }

    // Инициализация Swiper для отзывов
    function initializeReviewSwiper() {
        const swiperContainer = document.querySelector('.reviews-swiper');
        if (!swiperContainer) {
            console.log('Контейнер .reviews-swiper не найден');
            return;
        }

        // Проверяем, не инициализирован ли Swiper уже
        if (swiperContainer.swiper) {
            console.log('Swiper уже инициализирован для .reviews-swiper');
            return;
        }

        try {
            window.reviewSwiper = new Swiper('.reviews-swiper', {
                slidesPerView: 2,
                spaceBetween: 8,
                loop: false,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 8,
                    },
                },
                navigation: false,
                pagination: false,
            });
            console.log('Swiper для отзывов успешно инициализирован');
        } catch (error) {
            console.error('Ошибка при инициализации Swiper:', error);
        }
    }

    // Обработка элементов с data-toggle

    // Вызов инициализации
    initializeHeroSlider();
    initializeReviewSwiper();
}

// Слушаем события
document.addEventListener('DOMContentLoaded', initApp);
document.addEventListener('livewire:navigated', () => {
    console.log('Livewire navigated, переинициализация');
    // Уничтожаем Swiper, если он существует
    if (typeof window.reviewSwiper !== 'undefined' && window.reviewSwiper) {
        window.reviewSwiper.destroy(true, true);
        window.reviewSwiper = null;
        console.log('Swiper уничтожен');
    }
    initApp();
});
document.addEventListener('livewire:init', initApp);
document.addEventListener('livewire:update', initApp);

