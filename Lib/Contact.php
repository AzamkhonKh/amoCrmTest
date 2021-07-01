<?php

namespace Lib;

class Contact extends ConnectionAmoCRM
{
    public function getContactList(int $page = 0)
    {
        $this->url = $this->endpoint . $this->version . '/contacts';
        $query = [
            'with' => 'leads',
            'limit' => 250,
            //'query' => 'where count(_embedded[contacts][leads]) = 0'  -- может счтаться устрешвей
        ];
        $query['page'] = $page == 0 ? 1 : $page;
        return $this->request('GET', $query);
    }
}