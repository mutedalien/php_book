<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 07.09.18
 * Time: 12:46
 */

/**
 * Однако, параметры в адресной строке – это не всегда уместно. Например, когда в параметрах содержится какая-то конфиденциальная информация: пароль, пин-код. И любой мимо проходящий человек может её увидеть. Как в такой ситуации быть? Использовать POST-запросы!

 * Что это такое? Да всё тот же запрос от клиента к серверу, только параметры передаются внутри тела запроса, а не в адресной строке. И увидеть их просто так не получится.

 * Что за тело запроса? Ну, это просто данные, которые передаются на сервер. При этом они скрыты от лишних глаз.

 * Чтобы отправить POST-запрос нужно в HTML-форме задать для атрибута method значение POST.
 */
?>
<form action="login.php" method="post">
