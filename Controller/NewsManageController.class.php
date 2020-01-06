<?php
/**
 * User: jayinton
 * Date: 2019/12/21
 * Time: 21:17
 */

namespace News\Controller;


use Common\Controller\AdminBase;
use Foodshop\Service\ShopService;
use News\Service\NewsService;

class NewsManageController extends AdminBase
{
    function lists()
    {
        $this->display();
    }

    function edit()
    {
        $this->display();
    }

    function doEdit()
    {
        $data = I('post.');
        $data['detail_url'] = I('post.detail_url', '', '');
        $data['content'] = I('post.content', '', '');
        if (empty($data['id'])) {
            $data['create_time'] = time();
            $data['update_time'] = time();
            $res = NewsService::createItem($data);
        } else {
            $id = $data['id'];
            unset($data['id']);
            $data['update_time'] = time();
            $res = NewsService::updateItem($id, $data);
        }
        $this->ajaxReturn($res);
    }

    function doDelete()
    {
        $id = I('post.id');
        $res = NewsService::deleteItem($id);
        $this->ajaxReturn($res);
    }

    function getDetail()
    {
        $id = I('id');
        $res = NewsService::getById($id);
        $this->ajaxReturn($res);
    }

    function getList()
    {
        $name = I('title', '');
        $page = I('page', 1);
        $limit = I('limit', 15);
        $res = NewsService::getList(['title' => ['LIKE', '%' . $name . '%'], 'delete_time' => ['EQ', 0]], 'sort desc, id desc', $page, $limit);
        $this->ajaxReturn($res);
    }

}