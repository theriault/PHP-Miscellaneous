<?php
/**
 * Variation of native PHP base64_encode that allows arbitrary bytes for bytes
 * 63-64 and the padding character.
 * Capable of handling Base64 variations listed on:
 * https://en.wikipedia.org/wiki/Base64
 *
 * @param string $data The data to encode.
 * @param string $c 2 to 3 byte string. 1st byte will be byte 63. 2nd byte will
 *  be byte 64. 3rd byte (optional) will be padding character, defaults to =
 * @return string The encoded string or FALSE on failure.
 */
function base64_c_encode($data, $c = "+/=") {
	$data = (string) $data;
	$c = (string) $c;
	$l = strlen($c);
	if ($l === 2) $c .= "=";
	if (!preg_match("/^([^A-Z0-9])(?!\g1)([^A-Z0-9])(?!\g1|\g2)[^A-Z0-9]$/mis", $c)) {
		return false;
	}
	$r = base64_encode($data);
	if ($l === 2) $r = rtrim($r, "=");
	return $r !== false ? strtr($r, "+/=", $c) : false;
}
/**
 * Variation of native PHP base64_decode that allows arbitary bytes for bytes
 * 63-64 and the padding character.
 *
 * @param string $data The data to decode.
 * @param bool $strict 
 * @param string $c 2 to 3 byte string. 1st byte will be byte 63. 2nd byte will
 *  be byte 64. 3rd byte (optional) will be padding character, defaults to =
 * @return string The decoded string or FALSE on failure.
 */
function base64_c_decode($data, $strict = false, $c = "+/=") {
	$data = (string) $data;
	$strict = (bool) $strict;
	$c = (string) $c;
	if (strlen($c) === 2) $c .= "=";
	if (!preg_match("/^([^A-Z0-9])(?!\g1)([^A-Z0-9])(?!\g1|\g2)[^A-Z0-9]$/mis", $c)) {
		return false;
	}
	$r = strtr($data, $c, "+/=");
	return base64_decode($r, $strict);
}
