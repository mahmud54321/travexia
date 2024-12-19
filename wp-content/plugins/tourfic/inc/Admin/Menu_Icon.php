<?php 
namespace Tourfic\Admin;
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Menu_Icon {
	static $hotel_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyNC4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyMCAyMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjAgMjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiNBN0FBQUQ7fQ0KPC9zdHlsZT4NCjxnPg0KCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xMy40LDQuNWMtMC4zLTAuNS0xLTAuNy0yLjItMUw2LjgsMi4yYy0yLTAuNi0zLjEtMC45LTMuOC0wLjNDMi40LDIuNCwyLjQsMy41LDIuNCw1Ljd2MTIuOGgzdi0yDQoJCWMwLTAuOSwwLTEuNSwwLjQtMS45YzAuNC0wLjQsMS0wLjQsMS45LTAuNGgwLjljMC45LDAsMS41LDAsMS45LDAuNHMwLjQsMSwwLjQsMS45djJoMi41VjguNmMwLTAuMiwwLjEtMC4zLDAuMi0wLjQNCgkJQzEzLjUsOC4xLDEzLjcsOCwxMy44LDhWN0MxMy44LDUuNiwxMy44LDUsMTMuNCw0LjV6IE05LjQsMTEuOEg2LjhjLTAuMywwLTAuNi0wLjMtMC42LTAuNmMwLTAuMywwLjMtMC42LDAuNi0wLjZoMi42DQoJCWMwLjMsMCwwLjYsMC4zLDAuNiwwLjZDMTAsMTEuNSw5LjcsMTEuOCw5LjQsMTEuOHogTTkuNCw4LjNINi44QzYuNCw4LjMsNi4xLDgsNi4xLDcuN3MwLjMtMC42LDAuNi0wLjZoMi42YzAuMywwLDAuNiwwLjMsMC42LDAuNg0KCQlTOS43LDguMyw5LjQsOC4zeiBNOS42LDE1LjNjLTAuMS0wLjEtMC41LTAuMS0xLjEtMC4xSDcuNmMtMC42LDAtMSwwLTEuMSwwLjFjLTAuMSwwLjEtMC4xLDAuNi0wLjEsMS4xdjJoMy4zdi0yDQoJCUM5LjcsMTUuOSw5LjcsMTUuNCw5LjYsMTUuM3oiLz4NCgk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTcuNiwxMi41djZoLTMuM1Y5LjNsMS4yLDAuNGMwLDAsMS43LDAuNSwxLjksMC44QzE3LjYsMTAuOCwxNy42LDExLjMsMTcuNiwxMi41eiIvPg0KPC9nPg0KPC9zdmc+DQo=';
	static $tour_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyNC4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyMCAyMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjAgMjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiNBN0FBQUQ7fQ0KPC9zdHlsZT4NCjxnPg0KCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xNy40LDcuNGMtMC40LTAuMy0wLjktMC42LTEuNS0wLjdoLTJjLTEuMSwxLjYtMi43LDIuNi0zLjIsMi45Yy0wLjIsMC4xLTAuNCwwLjItMC42LDAuMnMtMC40LDAtMC42LTAuMg0KCQlDOSw5LjMsNy4zLDguMyw2LjIsNi43aC0yQzMuNSw2LjksMyw3LjEsMi42LDcuNGMtMSwwLjktMSwyLjMtMSw1YzAsMi44LDAsNC4yLDEsNS4xYzEsMC45LDIuNSwwLjksNS43LDAuOWgzLjMNCgkJYzMuMSwwLDQuNywwLDUuNy0wLjljMS0wLjksMS0yLjMsMS01LjFDMTguMyw5LjcsMTguMyw4LjMsMTcuNCw3LjR6IE02LjMsMTMuM0g0LjZjLTAuNCwwLTAuOC0wLjMtMC44LTAuOGMwLTAuNCwwLjMtMC44LDAuOC0wLjgNCgkJaDEuN2MwLjQsMCwwLjgsMC4zLDAuOCwwLjhDNywxMyw2LjcsMTMuMyw2LjMsMTMuM3ogTTEwLjgsMTMuM0g5LjJjLTAuNCwwLTAuOC0wLjMtMC44LTAuOGMwLTAuNCwwLjMtMC44LDAuOC0wLjhoMS43DQoJCWMwLjQsMCwwLjgsMC4zLDAuOCwwLjhDMTEuNiwxMywxMS4zLDEzLjMsMTAuOCwxMy4zeiBNMTUuNCwxMy4zaC0xLjdjLTAuNCwwLTAuOC0wLjMtMC44LTAuOGMwLTAuNCwwLjMtMC44LDAuOC0wLjhoMS43DQoJCWMwLjQsMCwwLjgsMC4zLDAuOCwwLjhDMTYuMiwxMywxNS44LDEzLjMsMTUuNCwxMy4zeiIvPg0KCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xMCw4LjJjMC4zLTAuMiwxLjEtMC43LDEuOC0xLjVDMTIuNiw2LDEzLjMsNSwxMy4zLDMuOGMwLTEuNy0xLjUtMy4xLTMuMy0zLjFTNi44LDIuMiw2LjgsMy44DQoJCUM2LjgsNSw3LjQsNiw4LjIsNi43QzguOSw3LjUsOS43LDgsMTAsOC4yeiBNMTAsM0wxMCwzYzAuNiwwLDEsMC40LDEsMXMtMC40LDEtMSwxUzksNC42LDksNFM5LjQsMywxMCwzeiIvPg0KPC9nPg0KPC9zdmc+DQo=';
	static $apt_icon = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyNC4wLjEsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCAyMCAyMCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMjAgMjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiNBN0FBQUQ7fQ0KPC9zdHlsZT4NCjxnPg0KCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xMC41LDMuM2w2LjYsNC43djcuNWMwLDEuMS0wLjksMi0yLDJoLTMuM1YxMmMwLTAuNS0wLjQtMS0xLTFoLTJjLTAuNSwwLTEsMC40LTEsMXY1LjZINS4zYy0xLjEsMC0yLTAuOS0yLTINCgkJVjguMUwxMCwzLjNDMTAuMiwzLjIsMTAuNCwzLjIsMTAuNSwzLjN6Ii8+DQoJPHBhdGggY2xhc3M9InN0MCIgZD0iTTE5LjQsNy43aC0xLjFsLTcuMS01LjFjLTAuNi0wLjQtMS40LTAuNC0yLDBsLTcsNS4xSDFsNy44LTUuNmMwLjktMC42LDItMC42LDIuOSwwTDE5LjQsNy43eiIvPg0KPC9nPg0KPC9zdmc+DQo=';
	static $room_icon = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik00IDEzSDE2SDE2LjVDMTYuNzc2MSAxMyAxNyAxMy4yMjM5IDE3IDEzLjVWMTVWMTYuNUMxNyAxNi43NzYxIDE2Ljc3NjEgMTcgMTYuNSAxN0MxNi4yMjM5IDE3IDE2IDE2Ljc3NjEgMTYgMTYuNVYxNS41QzE2IDE1LjIyMzkgMTUuNzc2MSAxNSAxNS41IDE1SDQuNUM0LjIyMzg2IDE1IDQgMTUuMjIzOSA0IDE1LjVWMTYuNUM0IDE2Ljc3NjEgMy43NzYxNCAxNyAzLjUgMTdDMy4yMjM4NiAxNyAzIDE2Ljc3NjEgMyAxNi41VjE1VjEzLjVDMyAxMy4yMjM5IDMuMjIzODYgMTMgMy41IDEzSDRaIiBmaWxsPSIjQTdBQUFEIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMyA2QzMgNC44OTU0MyAzLjg5NTQzIDQgNSA0SDE1QzE2LjEwNDYgNCAxNyA0Ljg5NTQzIDE3IDZWMTBIMTUuNVY4QzE1LjUgNi44OTU0MyAxNC42MDQ2IDYgMTMuNSA2SDEyLjVDMTEuMzk1NCA2IDEwLjUgNi44OTU0MyAxMC41IDhWMTBIOS41VjhDOS41IDYuODk1NDMgOC42MDQ1NyA2IDcuNSA2SDYuNUM1LjM5NTQzIDYgNC41IDYuODk1NDMgNC41IDhWMTBIM1Y2WiIgZmlsbD0iI0E3QUFBRCIvPgo8cGF0aCBkPSJNMiAxM1YxNC41QzIgMTQuNzc2MSAxLjc3NjE0IDE1IDEuNSAxNUMxLjIyMzg2IDE1IDEgMTQuNzc2MSAxIDE0LjVWMTIuNjk3MkMxIDEyLjI0MjYgMS4xMzQ1NyAxMS43OTgxIDEuMzg2NzUgMTEuNDE5OUwxLjgwNzUyIDEwLjc4ODdDMi41NjYzMSA5LjY1MDUzIDMuNzczOTggOC44ODk0MyA1LjEyODE2IDguNjk1OThMOC43MjcyMSA4LjE4MTgzQzkuNTcxNDUgOC4wNjEyMiAxMC40Mjg1IDguMDYxMjIgMTEuMjcyOCA4LjE4MTgzTDE0Ljg3MTggOC42OTU5OEMxNi4yMjYgOC44ODk0MyAxNy40MzM3IDkuNjUwNTMgMTguMTkyNSAxMC43ODg3TDE4LjYxMzIgMTEuNDE5OUMxOC44NjU0IDExLjc5ODEgMTkgMTIuMjQyNiAxOSAxMi42OTcyVjE0LjVDMTkgMTQuNzc2MSAxOC43NzYxIDE1IDE4LjUgMTVDMTguMjIzOSAxNSAxOCAxNC43NzYzIDE4IDE0LjUwMDFWMTNDMTggMTIuMiAxNy4zMzMzIDEyIDE3IDEySDNDMi4yIDEyIDIgMTIuNjY2NyAyIDEzWiIgZmlsbD0iI0E3QUFBRCIvPgo8L3N2Zz4K';
}