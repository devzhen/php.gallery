<h1>Фотогалерея</h1>

<strong>Функционал Backend:</strong>
<br>
<br>
Администратор сайта формирует список альбомов. Альбом имеет следующие свойства: <br>
• Название – текстовое поле <br>
• Дата создания – текстовое поле с выпадающим календариком<br>
• Описание альбома – большое текстовое поле <br>

В каждый альбом может быть загружено сколько угодно фотографий. 
Администратор имеет возможность сортировать / определять порядок вывода как фотографий внутри альбома, 
так и самих альбомов - перетаскивая элементы.

<strong>Функционал Frontend.</strong>
<br>
<br>
Посетители сайта видят список альбомов в мозаичном виде по 3 альбома в ряд, 12 альбомов на страницу. Реализована постраничная навигация при выводе списка альбомов.
Альбомы отсортированы в том порядке, какой им присвоил администратор в админке, а если порядок не определен, то по дате создания в обратном порядке (более свежие впереди).
В полной версии альбома крупно выводится название альбома, мелко дата создания и под ними следует описание альбома.
Под текстом выводятся все фотографии альбома без постраничной навигации. По 4 в ряд. 
