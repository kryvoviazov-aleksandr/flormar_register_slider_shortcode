=== Flormar Test Slider ===
Contributors: A.Kryvoviazov
Tags: WooCommerce, Slider, Products
Requires at least: 5.0
Tested up to: 6.7.2
Stable tag: 1.0.0


== Description ==
Flormar Test Slider – це кастомний слайдер для WooCommerce, який відображає товари з можливістю фільтрації за ціною та виводить вказану кількість товару, якщо поля пусті відображаються всі товари.

== Installation ==
1. Завантажте плагін у `/wp-content/plugins/` або встановіть через WordPress.
2. Активуйте плагін у меню "Плагіни".
3. Використовуйте шорткод `[flormar-test-slider]` для виведення слайдера.

== Settings ==
Перейдіть у **"Налаштування" → "Flormar Slider"**, щоб змінити параметри:
- **Заголовок слайдера**
- **Мінімальна ціна товарів**
- **Максимальна ціна товарів**
- **Кількість товарів у слайдері** (якщо не вказано, виводяться всі)

== Changelog ==
= 1.0 =
* Додано шорткод `[flormar-test-slider]`
* Додано налаштування фільтрації товарів
* Додано адаптивність для мобільних і планшетів

## 🚀 Що можна покращити?

Хоча плагін повністю відповідає ТЗ, є можливості для подальшого розвитку:

### 🔹 1. **Додати підтримку категорій**
   📌 Додати фільтрацію товарів за категоріями прямо з адмінки.  
   📌 Можна зробити випадаючий список категорій у налаштуваннях плагіна.  

### 🔹 2. **Динамічне завантаження товарів (AJAX)**
   📌 Зараз кількість товарів фіксована.  
   📌 Додати можливість підвантажувати більше товарів без перезавантаження сторінки.  

### 🔹 3. **Додати можливість вибору сортування**
   📌 Додати опцію в адмінці для вибору сортування товарів (за ціною, популярністю, новизною).  

### 🔹 4. **Можливість змінювати стиль слайдера**
   📌 Додати вибір дизайну (світла/темна тема) у налаштуваннях.  
   📌 Додати вибір кількості товарів в одному слайді (зараз 4 на десктопі, 2 на планшеті, 1 на мобільному).  

### 🔹 5. **Оптимізація завантаження зображень**
   📌 Використовувати WebP-версії зображень, якщо підтримується сервером.  
   📌 Ліниве завантаження (`lazy-loading`) для покращення продуктивності.  




