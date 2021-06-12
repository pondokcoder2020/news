<?php

namespace PondokCoder;

use PondokCoder\Authorization as Authorization;
use PondokCoder\Query as Query;
use PondokCoder\QueryException as QueryException;
use PondokCoder\Utility as Utility;


class Berita extends Utility
{
    static $pdo;
    static $query;

    protected static function getConn()
    {
        return self::$pdo;
    }

    public function __construct($connection)
    {
        self::$pdo = $connection;
        self::$query = new Query(self::$pdo);
    }

    public function __GET__($parameter = array())
    {
        switch ($parameter[1]) {
            case 'detail_kategori':
                return self::detail_kategori($parameter[2]);
                break;
        }
    }

    public function __POST__($parameter = array())
    {
        switch ($parameter['request']) {
            case 'get_kategori':
                return self::get_kategori($parameter);
                break;
            case 'get_berita':
                return self::get_berita($parameter);
                break;
            case 'add_kategori':
                return self::add_kategori($parameter);
                break;
            case 'edit_kategori':
                return self::edit_kategori($parameter);
                break;
            default:
                return array();
                break;
        }
    }

    public function __DELETE__($parameter = array())
    {
        switch ($parameter[7]) {
            case 'kategori':
                return self::delete_kategori($parameter[8]);
                break;
            default:
                return $parameter[7];
                break;
        }
    }

    private function detail_kategori($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $Data = self::$query->select('news_cathegory', array(
            'uid', 'name', 'description'
        ))
            ->where(array(
                'news_cathegory.uid' => '= ?',
                'AND',
                'news_cathegory.deleted_at' => 'IS NULL'
            ), array(
                $parameter
            ))
            ->execute();

        return $Data;
    }

    private function delete_kategori($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Data = self::$query->delete('news_cathegory')
            ->where(array(
                'news_cathegory.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        return $Data;
    }

    private function add_kategori($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $uid = self::gen_uuid();
        $Data = self::$query->insert('news_cathegory', array(
            'uid' => $uid,
            'name' => $parameter['nama'],
            'description' => $parameter['keterangan'],
            'created_at' => parent::format_date(),
            'updated_at' => parent::format_date()
        ))
            ->execute();

        return $Data;
    }

    private function edit_kategori($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Data = self::$query->update('news_cathegory', array(
            'name' => $parameter['nama'],
            'description' => $parameter['keterangan'],
            'updated_at' => parent::format_date()
        ))
            ->where(array(
                'news_cathegory.uid' => '= ?',
                'AND',
                'news_cathegory.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        return $Data;
    }

    private function get_kategori($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'news_cathegory.deleted_at' => 'IS NULL',
                'AND',
                '(news_cathegory.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'news_cathegory.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')'
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'news_cathegory.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('news_cathegory', array(
                'uid',
                'name',
                'description',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('news_cathegory', array(
                'uid',
                'name',
                'description',
                'created_at',
                'updated_at'
            ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $itemTotal = self::$query->select('news_cathegory', array(
            'uid'
        ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }

    private function get_berita($parameter) {
        if (isset($parameter['search']['value']) && !empty($parameter['search']['value'])) {
            $paramData = array(
                'news.deleted_at' => 'IS NULL',
                'AND',
                '(news.title' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'news.content_short' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\'',
                'OR',
                'news.content_long' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\')',
                'OR',
                'penulis.nama' => 'ILIKE ' . '\'%' . $parameter['search']['value'] . '%\''
            );

            $paramValue = array();
        } else {
            $paramData = array(
                'news.deleted_at' => 'IS NULL'
            );

            $paramValue = array();
        }


        if ($parameter['length'] < 0) {
            $data = self::$query->select('news', array(
                'uid',
                'creator',
                'title',
                'content_short',
                'content_long',
                'cathegory',
                'meta_description',
                'meta_tag',
                'featured',
                'penulis',
                'created_at',
                'updated_at'
            ))
                ->join('penulis', array(
                    'email',
                    'nama',
                    'twitter',
                    'facebook',
                    'kontak',
                    'website'
                ))
                ->on(array(
                    array('news.penulis', '=', 'penulis.uid')
                ))
                ->where($paramData, $paramValue)
                ->execute();
        } else {
            $data = self::$query->select('news', array(
                'uid',
                'creator',
                'title',
                'content_short',
                'content_long',
                'cathegory',
                'meta_description',
                'meta_tag',
                'featured',
                'penulis',
                'created_at',
                'updated_at'
            ))
                ->join('penulis', array(
                    'email',
                    'nama',
                    'twitter',
                    'facebook',
                    'kontak',
                    'website'
                ))
                ->on(array(
                    array('news.penulis', '=', 'penulis.uid')
                ))
                ->where($paramData, $paramValue)
                ->offset(intval($parameter['start']))
                ->limit(intval($parameter['length']))
                ->execute();
        }

        $data['response_draw'] = $parameter['draw'];
        $autonum = intval($parameter['start']) + 1;
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['autonum'] = $autonum;
            $autonum++;
        }

        $itemTotal = self::$query->select('news', array(
            'uid',
            'penulis'
        ))
            ->join('penulis', array(
                'email',
                'nama'
            ))
            ->on(array(
                array('news.penulis', '=', 'penulis.uid')
            ))
            ->where($paramData, $paramValue)
            ->execute();

        $data['recordsTotal'] = count($itemTotal['response_data']);
        $data['recordsFiltered'] = count($itemTotal['response_data']);
        $data['length'] = intval($parameter['length']);
        $data['start'] = intval($parameter['start']);

        return $data;
    }
}