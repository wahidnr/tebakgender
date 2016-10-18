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
  $file_data_siswa = __DIR__ . '/../data_siswa.csv';
  $file_model_tebakgender = __DIR__ . '/../model_tebakgender.csv';

  $data_siswa = $request->getUploadedFiles()['data_siswa'];
  $moved = move_uploaded_file($data_siswa->file, $file_data_siswa);

  file_put_contents($file_model_tebakgender, gawe_model(file_get_contents($file_data_siswa)));

  if ($data_siswa->file && !$moved) return $response->withStatus(500);
  return $response->withStatus(302)->withHeader('Location', '/api/update');
});

function gawe_model($data_siswa) {
  foreach (explode(PHP_EOL, $data_siswa) as $baris) {
    list($nisn, $nama, $gender, $tempat_lahir, $tanggal_lahir) = explode(',', $baris);
    foreach (pecah(normalisasi($nama)) as $pecahan)
      ++$frekuensi[$pecahan][$gender == 'P'];
  }
  foreach ($frekuensi as $pecahan => list($lanang, $wadon)) {
    $model .= $pecahan.','.(int)$lanang.','.(int)$wadon.PHP_EOL;
  }
  return $model;
}

function pecah($jengen) {
  foreach (explode(' ', $jengen) as $kata) {
    $pecahan[] = $kata;
    if (strlen($kata) > 4) $pecahan[] = substr($kata, -4);
    if (strlen($kata) > 3) $pecahan[] = substr($kata, -3);
  }
  return $pecahan;
}

function normalisasi($jengen) {
  $koreksi = [
    ['KH', 'H'],
    ['CH', 'H'],
    ['SY', 'S'],
    ['DH', 'D'],

    ['MM', 'M'],
    ['DD', 'D'],
    ['ZZ', 'Z'],
    ['BB', 'B'],
    ['LL', 'L'],

    ['Y', 'I'],
    ['Q', 'K'],
    ['Z', 'S'],

    ['IE', 'I'],
    ['II', 'I'],
  ];
  foreach ($koreksi as list($salah, $bener))
    $jengen = str_replace($salah, $bener, $jengen);
  return $jengen;
}
