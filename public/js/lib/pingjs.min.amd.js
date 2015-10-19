/**
 * Thanks to jdfreder
 * @link https://github.com/jdfreder/pingjs
 */

define(function(){var n;return n={request_image:function(n){return new Promise(function(e,t){var r=new Image;r.onload=function(){e(r)},r.onerror=function(){t(n)},r.src=n+"?random-no-cache="+Math.floor(65536*(1+Math.random())).toString(16)})},ping:function(e,t){return new Promise(function(r,o){var i=(new Date).getTime(),a=function(){var n=(new Date).getTime()-i;n*=t||1,r(n)};n.request_image(e).then(a)["catch"](a),setTimeout(function(){o(Error("Timeout"))},5e3)})}}});