<?php
/**
 * User: jayinton
 * Date: 2019/12/20
 * Time: 17:57
 */

namespace News\Service;


use System\Service\BaseService;

class NewsService extends BaseService
{
    /**
     * 根据ID获取
     *
     * @param $id
     * @return array
     */
    static function getById($id)
    {
        $res = self::find('News/News', ['id' => $id]);
        $res['data']['images'] = json_decode($res['data']['images'], 1);
        if (empty($res['data']['images'])) {
            $res['data']['images'] = [];
        }

        return $res;
    }


    /**
     * 获取列表
     *
     * @param array $where
     * @param string $order
     * @param int $page
     * @param int $limit
     * @param bool $isRelation
     * @return array
     */
    static function getList($where = [], $order = '', $page = 1, $limit = 20, $isRelation = false)
    {
        $res = self::select('News/News', $where, $order, $page, $limit, $isRelation);
        foreach ($res['data']['items'] as &$item) {
            $item['images'] = json_decode($item['images'], 1);
            if (empty($item['images'])) {
                $item['images'] = [];
            }
        }
        return $res;
    }

    /**
     * 添加
     *
     * @param array $data
     * @return array
     */
    static function createItem($data = [])
    {
        if (is_array($data['images'])) {
            $data['images'] = json_encode($data['images']);
        }
        if (empty($data['release_date'])) {
            $data['release_date'] = date('Y-m-d');
        }
        $data['release_time'] = strtotime($data['release_date']);
        return self::create('News/News', $data);
    }

    /**
     * 更新
     *
     * @param       $id
     * @param array $data
     * @return array
     */
    static function updateItem($id, $data = [])
    {
        if (is_array($data['images'])) {
            $data['images'] = json_encode($data['images']);
        }
        if (empty($data['release_date'])) {
            $data['release_date'] = date('Y-m-d');
        }
        $data['release_time'] = strtotime($data['release_date']);
        return self::update('News/News', ['id' => $id], $data);
    }

    /**
     * 删除
     *
     * @param $id
     * @return array
     */
    static function deleteItem($id)
    {
        return self::delete('News/News', ['id' => $id]);
    }
}