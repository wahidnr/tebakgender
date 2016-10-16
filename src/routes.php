<?php
// Routes

/*
$app->get('/{name}', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/

$app->get('/api/update', function ($request, $response, $args) {
  return $this->renderer->render($response, 'update.phtml', $args);
});

$app->post('/api/update/upload', function ($request, $response, $args) {
  $data_siswa = $request->getUploadedFiles()['data_siswa'];
  $moved = move_uploaded_file($data_siswa->file, __DIR__ . '/../data_siswa.csv');
  if ($data_siswa->file && !$moved)return $response->withStatus(500);
  return $response->withStatus(302)->withHeader('Location', '/api/update');
});
