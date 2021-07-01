<?php
use Lib\Contact;
use Lib\ConnectionAmoCRM;
use Lib\Todo;

// Логика проста : взять взять контакты
$array_contact_id = array();
$page = 1;
$contact = new Contact();
$api = new ConnectionAmoCRM();
$list = $contact->getContactList($page);
while ($page < 250) {
    $next = $list->_links->next;
    $contacts = $list->_embedded->contacts;
    foreach ($contacts as $contact) {
        if (empty($contact->_embedded->leads)) $array_contact_id[] = $contact->id;
    }
    $api->url = $next;
    $list = $api->request('GET');
    if (is_string($list) && substr($list, 0, 6) === "error:") {
        echo $list;
        break;
    }
    $page++;
}
// теперь нужно найти контакты без сделок
echo "Список id контактов без сделок :" . implode(',', $array_contact_id);
// каждому из них открыть задачу с текстом  “Контакт без сделок”.
$todo = new Todo();
$array_todo_ids = array();
foreach ($array_contact_id as $id) {
    $result = $todo->addTaskContact(['contact_id' => $id, 'text' => 'Контакт без сделок']);
    if (is_string($result) && substr($result, 0, 6) === "error:") {
        echo $result;
        break;
    }
    $array_todo_ids[] = $result->id;
}
echo "Список id задач которые были созданы :" . implode(',', $array_todo_ids);
echo "Скрипт завершен !";
