<?php
require_once 'config.php';

function index()
{
    global $db;
    $result = $db?->table('post')?->get_all();
    echo json_encode($result);
}

function store($data)
{
    if (!$data['title'] || !$data['content']) {
        http_response_code(422);
        exit('Vui lòng điền đầy đủ trường!!!');
    }

    global $db;
    $result = $db?->table('post')?->insert(['title' => $data['title'], 'content' => $data['content']]);
    echo json_encode($result);
}

function update($data)
{
    global $db;
    $result = $db?->table('post')?->update(['id' => (int) $data['id']], [
        'title' => $data['title'],
        'content' => $data['content'],
    ]);

    echo 'đã sửa ' . $result . ' bài post';
}

function delete($id)
{
    if (!$id) {
        http_response_code(422);
        exit('Có lỗi trong quá trình xoá!!!');
    }

    global $db;
    $result = $db?->table('post')?->delete(['id' => $id]);
    if ($result >= 1) {
        echo 'đã xoá ' . $result . ' bài post';
    }
}

$path = $_SERVER['REQUEST_URI'];

if (preg_match('/get=true/i', $path)) {
    index();
} else if (preg_match('/update=true/i', $path)) {
    update($_POST);
} else if (preg_match('/delete=true/i', $path)) {
    delete($_POST['id']);
} else if (preg_match('/add=true/i', $path)) {
    store($_POST);
}
