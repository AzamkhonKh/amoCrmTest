<?php

namespace Lib;

use Exception;

class Todo extends ConnectionAmoCRM
{
    public function addTaskContact(array $data)
    {
        try {
            if (!isset($data['text']) || empty($data['text'])) throw new Exception("text input is invalid");
            if (!isset($data['contact_id']) || empty($data['contact_id'])) throw new Exception("contact_id input is invalid");

        } catch (Exception $e) {
            return 'error: ' . $e->getMessage();
        }
        $complete_till = date('Y-m-d H:m:s', strtotime(date("Y-m-d H:i:s"). ' + 1 days'));
        $this->url = $this->endpoint . $this->version . '/tasks';
        $body = [
            'complete_till' => strtotime($complete_till),
            'text' => $data['text'],
            'entity_id' => $data['contact_id'],
            'entity' => 'contact'
        ];
        return $this->request('POST', [], $body);
    }
}