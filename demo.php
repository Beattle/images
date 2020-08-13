<?php
require 'Mobile_Detect.php';

$Mobile_Detect = new Mobile_Detect();
$images = [];
$size = 'min';
if ($Mobile_Detect->isMobile()) {
	$size = 'mic';
}


foreach (new DirectoryIterator('images/') as $file) {
	if ($file->isFile()) {
		$file_name = $file->getBasename('.' . $file->getExtension());
		if (in_array($file_name, $images)) {
			$file_name = $file->getPath() . '/' . $file_name . uniqid();
			rename($file->getPathname(), $file_name . '.' . $file->getExtension());
		}
		$images[] = $file_name;
	}
}
if (empty($images)) {
	die('Картинок не найдено');
}

?>
<!DOCTYPE html >
<html lang="ru">
<head>
	<meta charset="UTF-8" />
	<title>Галлерея</title>
	<link
		rel="stylesheet"
		href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
		crossorigin="anonymous"
	>
	<!-- 1. Add latest jQuery and fancybox files -->

	<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"></script>

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css"/>
	<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
</head>
<body>
<div class="container">
	<div class="row">
		<?php foreach ($images as $image): ?>
			<a class="fancy-cat" data-name="<?= $image ?>" data-size="<?= $size ?>" href="javascript:void(0)">
				<img
					alt="<?= $image ?>"
					src="generator.php?name=<?= $image ?>&size=<?= $size ?>"
				/>
			</a>
		<?php endforeach; ?>
	</div>
</div>

<script>


  $('.fancy-cat').on('click', function () {
    let formData = new FormData()
    formData.append('name', this.dataset.name)
    formData.append('size', this.dataset.size)

    fetch('generator.php', {
      method: 'POST',
      body: formData,
    })
      .then(response => response.json())
      .then((data) => {
        let gallery = []
        for (let pic of data) {
          let item = {
            src: pic['link'],
            opts: {
              thumb: pic['link']
            }
          }
          gallery.push(item)
        }
        $.fancybox.open(gallery, { thumbs: { autoStart: true } })
      })

  })
</script>
</body>
</html>

