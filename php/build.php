<?php

require_once __DIR__ . '/data.php';
require_once __DIR__ . '/template.php';

$command = $argv[1] ?? '';

match ($command) {
	'index' => buildIndex(),
	'detail' => buildDetail($argv[2] ?? ''),
	default => die("Usage: php build.php {index|detail <slug>}\n"),
};

function buildIndex(): void {
	global $CATEGORIES, $SOFTWARE, $COLORS;

	layout([
		'title'       => 'Phần mềm miễn phí - Tổng hợp phần mềm miễn phí tốt nhất cho văn phòng, học sinh, sinh viên',
		'description' => 'Tổng hợp những phần mềm miễn phí tốt nhất cho văn phòng, học sinh, sinh viên và người dùng phổ thông.',
		'content'     => renderHomepage($CATEGORIES, $SOFTWARE, $COLORS),
	]);
}

function buildDetail(string $slug): void {
	global $SOFTWARE, $CATEGORIES;

	$sw = null;
	foreach ($SOFTWARE as $item) {
		if ($item['id'] === $slug) {
			$sw = $item;
			break;
		}
	}

	if (!$sw) {
		fwrite(STDERR, "Error: Software '$slug' not found.\n");
		exit(1);
	}

	$cat = $CATEGORIES[$sw['cat']] ?? ['name' => ''];

	layout([
		'title'       => $sw['name'] . ' - Phần mềm miễn phí',
		'description' => $sw['desc'],
		'content'     => renderDetail($sw, $cat),
	]);
}
