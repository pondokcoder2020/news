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
            case 'detail_berita':
                return self::detail_berita($parameter[2]);
                break;
            case 'kategori_select2':
                return self::kategori_select2($parameter);
                break;
            case 'penulis_select2':
                return self::penulis_select2($parameter);
                break;
            case 'get_kategori':
                return self::front_get_kategori($parameter);
                break;
            case 'front_cathegory_news':
                return self::front_cathegory_news($parameter);
                break;
            case 'front_all_news':
                return self::front_all_news($parameter);
                break;
            default:
                return array();
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
            case 'tambah_penulis_saya':
                return self::tambah_penulis_saya($parameter);
                break;
            case 'add_berita':
                return self::add_berita($parameter);
                break;
            case 'edit_berita':
                return self::edit_berita($parameter);
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
            case 'berita':
                return self::delete_berita($parameter[8]);
                break;
            default:
                return $parameter[7];
                break;
        }
    }

    private function edit_berita($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $uid = parent::gen_uuid();
        $data = self::$query->update('news', array(
            'creator' => $UserData['data']->uid,
            'title' => $parameter['judul'],
            'content_short' => $parameter['content_short'],
            'content_long' => $parameter['content_long'],
            'cathegory' => $parameter['kategori'],
            'featured' => strtoupper($parameter['featured']),
            'penulis' => $parameter['penulis'],
            'published' => ($parameter['publish'] === 'Y') ? parent::format_date(): null,
            'meta_title' => $parameter['meta_title'],
            'meta_tag' => $parameter['meta_tag'],
            'meta_description' => $parameter['meta_description'],
            'updated_at' =>  parent::format_date()
        ))
            ->where(array(
                'news.uid' => '= ?',
                'AND',
                'news.deleted_at' => 'IS NULL'
            ), array(
                $parameter['uid']
            ))
            ->execute();

        if($data['response_result'] > 0) {
            //SS Upload
            $data = $parameter['ImageSS'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            if (!file_exists('../images/berita')) {
                mkdir('../images/berita');
            }
            file_put_contents('../images/berita/SS' . $parameter['uid'] . '.png', $data);

            //TN Upload
            $data = $parameter['ImageTN'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            if (!file_exists('../images/berita')) {
                mkdir('../images/berita');
            }
            file_put_contents('../images/berita/TN' . $parameter['uid'] . '.png', $data);

            //SP Upload
            $data = $parameter['ImageSP'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            if (!file_exists('../images/berita')) {
                mkdir('../images/berita');
            }
            file_put_contents('../images/berita/SP' . $parameter['uid'] . '.png', $data);
        }

        return $data;
    }

    private function add_berita($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        $uid = parent::gen_uuid();
        $data = self::$query->insert('news', array(
            'uid' => $uid,
            'creator' => $UserData['data']->uid,
            'title' => $parameter['judul'],
            'content_short' => $parameter['content_short'],
            'content_long' => $parameter['content_long'],
            'cathegory' => $parameter['kategori'],
            'featured' => strtoupper($parameter['featured']),
            'penulis' => $parameter['penulis'],
            'published' => ($parameter['publish'] === 'Y') ? parent::format_date(): null,
            'meta_title' => $parameter['meta_title'],
            'meta_tag' => $parameter['meta_tag'],
            'meta_description' => $parameter['meta_description'],
            'created_at' => parent::format_date(),
            'updated_at' =>  parent::format_date()
        ))
            ->execute();

        if($data['response_result'] > 0) {
            //SS Upload
            $data = $parameter['ImageSS'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            if (!file_exists('../images/berita')) {
                mkdir('../images/berita');
            }
            file_put_contents('../images/berita/SS' . $uid . '.png', $data);

            //TN Upload
            $data = $parameter['ImageTN'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            if (!file_exists('../images/berita')) {
                mkdir('../images/berita');
            }
            file_put_contents('../images/berita/TN' . $uid . '.png', $data);

            //SP Upload
            $data = $parameter['ImageSP'];
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);
            if (!file_exists('../images/berita')) {
                mkdir('../images/berita');
            }
            file_put_contents('../images/berita/SP' . $uid . '.png', $data);
        }

        return $data;
    }

    private function tambah_penulis_saya($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);

        //Check Penulis
        $check = self::$query->select('penulis', array(
            'uid', 'nama'
        ))
            ->where(array(
                'penulis.uid' => '= ?',
                'AND',
                'penulis.deleted_at' => 'IS NULL'
            ), array(
                $UserData['data']->uid
            ))
            ->execute();
        if(count($check['response_data']) > 0) {
            return $check;
        } else {
            $new = self::$query->insert('penulis', array(
                'uid' => $UserData['data']->uid,
                'nama' => $UserData['data']->nama,
                'created_at' => parent::format_date(),
                'updated_at' => parent::format_date()
            ))
                ->execute();
            return array(
                'response_data' => array(
                    array(
                        'uid' => $UserData['data']->uid,
                        'nama' => $UserData['data']->nama
                    )
                )
            );
        }
    }

    private function penulis_select2($parameter) {
        $data = self::$query->select('penulis', array(
            'uid',
            'nama',
            'email',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'penulis.nama' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                'AND',
                'penulis.deleted_at' => 'IS NULL'
            ), array(

            ))
            ->execute();
        return $data;
    }

    private function kategori_select2($parameter) {
        $data = self::$query->select('news_cathegory', array(
            'uid',
            'name',
            'description',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'news_cathegory.name' => 'ILIKE ' . '\'%' . $_GET['search'] . '%\'',
                'AND',
                'news_cathegory.deleted_at' => 'IS NULL'
            ), array(

            ))
            ->execute();
        return $data;
    }

    private function detail_berita($parameter) {
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
            ->where(array(
                'news.deleted_at' => 'IS NULL',
                'AND',
                'news.uid' => '= ?'
            ), array(
                $parameter
            ))
            ->execute();
        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
            $data['response_data'][$key]['cathegory_detail'] = self::detail_kategori($value['cathegory'])['response_data'][0];
        }

        return $data;
    }

    private function detail_kategori($parameter) {
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

    private function delete_berita($parameter) {
        $Authorization = new Authorization();
        $UserData = $Authorization->readBearerToken($parameter['access_token']);
        $Data = self::$query->delete('news')
            ->where(array(
                'news.uid' => '= ?'
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

    private function front_get_kategori($parameter) {
        $data = self::$query->select('news_cathegory', array(
            'uid',
            'name',
            'description',
            'created_at',
            'updated_at'
        ))
            ->where(array(
                'news_cathegory.deleted_at' => 'IS NULL'
            ), array())
            ->execute();
        return $data;
    }

    private function front_all_news($parameter) {
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
            ->where(array(
                'news.deleted_at' => 'IS NULL'
            ), array())
            ->order(array(
                'news.created_at' => 'DESC'
            ))
            ->execute();

        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
        }

        return $data;
    }

    private function front_cathegory_news($parameter) {
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
            ->where(array(
                'news.cathegory' => '= ?',
                'AND',
                'news.deleted_at' => 'IS NULL'
            ), array(
                $parameter[2]
            ))
            ->execute();

        foreach ($data['response_data'] as $key => $value) {
            $data['response_data'][$key]['created_at_parsed'] = date('d F Y', strtotime($value['created_at']));
        }

        return $data;
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