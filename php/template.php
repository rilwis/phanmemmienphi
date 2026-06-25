<?php

function layout(array $vars): void {
	$title       = $vars['title'];
	$description = $vars['description'];
	$content     = $vars['content'];
	?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($title) ?></title>
	<link rel="icon" href="/images/icon.webp">
	<link rel="apple-touch-icon" href="/images/icon.webp">
	<meta name="description" content="<?= htmlspecialchars($description) ?>">
	<link rel="stylesheet" href="/css/style.css">
</head>
<body>
	<header class="site-header">
		<div class="container flex">
			<a href="/" class="site-title flex">
				<img src="/images/icon.webp" alt="Phần mềm miễn phí" width="32" height="32">
				Phần mềm miễn phí
			</a>
			<nav class="site-nav flex">
				<a href="/#van-phong">Văn phòng</a>
				<a href="/#tien-ich">Tiện ích</a>
				<a href="/#media">Media</a>
				<a href="/#mang">Mạng</a>
				<a href="/#bao-mat">Bảo mật</a>
			</nav>
		</div>
	</header>

	<main class="site-main">
		<div class="container">
			<?= $content ?>
		</div>
	</main>

	<footer class="site-footer">
		<div class="container">
			<p>Phần mềm miễn phí - Tổng hợp phần mềm miễn phí cho người dùng Việt Nam.</p>
			<p>Website giới thiệu - Không chịu trách nhiệm về nội dung của phần mềm bên thứ ba.</p>
		</div>
	</footer>

</body>
</html>
<?php
}

function renderHomepage(array $categories, array $software, array $colors): string {
	ksort($categories);
	uasort($categories, fn($a, $b) => $a['order'] - $b['order']);

	$html = '<section class="hero">
	<h1>Phần mềm miễn phí</h1>
	<p>Tổng hợp những phần mềm miễn phí tốt nhất cho văn phòng, học sinh, sinh viên và người dùng phổ thông.</p>
</section>';

	$colorIdx = 0;

	foreach ($categories as $catId => $cat) {
		$items = array_values(array_filter($software, fn($s) => $s['cat'] === $catId));
		if (empty($items)) continue;

		$html .= '<section class="category" id="' . $catId . '">
	<div class="category-header flex">
		<div class="category-icon flex" style="background: ' . $colors[$colorIdx % count($colors)] . '">' . mb_substr($cat['name'], 0, 1) . '</div>
		<h2>' . $cat['name'] . '</h2>
	</div>
	<div class="grid">';
		foreach ($items as $sw) {
			$html .= '<a href="p/' . $sw['id'] . '.html" class="card">
		<img class="icon" src="images/icons/' . $sw['id'] . '.webp" alt="' . htmlspecialchars($sw['name']) . '" width="48" height="48" loading="lazy">
		<div class="info">
			<h3>' . htmlspecialchars($sw['name']) . '</h3>
			<p>' . htmlspecialchars($sw['desc']) . '</p>
		</div>
		<span class="arrow">→</span>
	</a>';
			$colorIdx++;
		}

		$html .= '</div></section>';
	}

	return $html;
}

function renderDetail(array $sw, array $cat): string {
	$paras = '';
	foreach (explode("\n\n", $sw['intro']) as $p) {
		$p = trim($p);
		if ($p === '') continue;
		$paras .= '<p>' . nl2br(htmlspecialchars($p)) . "</p>\n";
	}

	$downloadBtn = $sw['download']
		? '<a href="' . htmlspecialchars($sw['download']) . '" class="btn btn-secondary" target="_blank" rel="noopener">
			Tải xuống ↓
		</a>'
		: '';

	return '
<div class="detail-header">
	<div class="breadcrumb">
		<a href="/">Trang chủ</a>
		<span class="separator">/</span>
		<a href="/#' . $sw['cat'] . '">' . htmlspecialchars($cat['name']) . '</a>
		<span class="separator">/</span>
		<span>' . htmlspecialchars($sw['name']) . '</span>
	</div>
	<div class="detail-title flex">
		<img class="detail-icon" src="../images/icons/' . $sw['id'] . '.webp" alt="' . htmlspecialchars($sw['name']) . '" width="64" height="64">
		<h1 class="detail-name">' . htmlspecialchars($sw['name']) . '</h1>
	</div>
	<p class="detail-description">' . htmlspecialchars($sw['desc']) . '</p>
</div>
<div class="detail-content">
	<div class="detail-body">
		<img class="screenshot" src="../images/screenshots/' . $sw['id'] . '.webp" alt="Ảnh chụp màn hình ' . htmlspecialchars($sw['name']) . '" loading="lazy">
		' . $paras . '
	</div>
	<aside class="sidebar">
		<div class="sidebar-card">
			<div class="sidebar-heading">Liên kết</div>
			<div class="btn-group flex">
				<a href="' . htmlspecialchars($sw['url']) . '" class="btn btn-primary" target="_blank" rel="noopener">
				Trang chủ →
				</a>
				' . $downloadBtn . '
			</div>
		</div>
	</aside>
</div>';
}
