<?
use Bitrix\Main\Localization\Loc,
	Aspro\Lite\Sender\Preset\Template;

Loc::loadMessages(__FILE__);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251">
		<title>email template</title>

		<style type="text/css">
			a{
				word-wrap: break-word;
			}
			table{
				border-collapse: collapse;
				table-layout: fixed;
			}
			h1,h2,h3,h4,h5,h6{
				display: block;
				margin: 0;
				padding: 0;
			}
			img,a img{
				border: 0;
				margin: 0;
				outline: none;
				text-decoration: none;
			}
			body{
				height: 100% !important;
				margin: 0;
				padding: 0;
				font-family: Arial, Helvetica;
				padding-right: 2px;
				min-width: 268px !important;
			}
			img{
				-ms-interpolation-mode: bicubic;
			}
			table{
				mso-table-lspace: 0pt;
				mso-table-rspace: 0pt;
			}
			p,a,li,td{
				mso-line-height-rule: exactly;
			}
			p,a,li,td,body,table,blockquote{
				-ms-text-size-adjust: 100%;
				-webkit-text-size-adjust: 100%;
			}
			.mail-wrap{
				width: 100%;
				max-width: 600px;
				margin: 20px auto;
				overflow: hidden;
				background-color: transparent;
			}
			.mail-grid .mail-grid{
				width: 100% !important;
			}
			a{
				text-decoration: none;
				color: <?=$baseColor?>;
			}

			#bxStylistHeader [data-bx-block-editor-place="leftColumnHeader"] p, #bxStylistHeader [data-bx-block-editor-place="leftColumnHeader"] a:not(.bxBlockContentButton), #bxStylistHeader [data-bx-block-editor-place="leftColumnHeader"] span, #bxStylistHeader [data-bx-block-editor-place="leftColumnHeader"] table{
				text-align: left;
			}

			#bxStylistHeader [data-bx-block-editor-place="rightColumnHeader"] p, #bxStylistHeader [data-bx-block-editor-place="rightColumnHeader"] a:not(.bxBlockContentButton), #bxStylistHeader [data-bx-block-editor-place="rightColumnHeader"] span, #bxStylistHeader [data-bx-block-editor-place="rightColumnHeader"] table{
				text-align: right;
			}

			#bxStylistHeader [data-bx-block-editor-place="header"] p, #bxStylistHeader [data-bx-block-editor-place="header"] a:not(.bxBlockContentButton), #bxStylistHeader [data-bx-block-editor-place="header"] span, #bxStylistHeader [data-bx-block-editor-place="header"] table{
				text-align: center;
			}

			#bxStylistBody [data-bx-block-editor-place="body"] p, #bxStylistBody [data-bx-block-editor-place="body"] a:not(.bxBlockContentButton), #bxStylistBody [data-bx-block-editor-place="body"] span, #bxStylistBody [data-bx-block-editor-place="body"] table{
				text-align: left;
			}

			#bxStylistFooter [data-bx-block-editor-place="leftColumnFooter"] p, #bxStylistFooter [data-bx-block-editor-place="leftColumnFooter"] a:not(.bxBlockContentButton), #bxStylistFooter [data-bx-block-editor-place="leftColumnFooter"] span, #bxStylistFooter [data-bx-block-editor-place="leftColumnFooter"] table{
				text-align: left;
			}

			#bxStylistFooter [data-bx-block-editor-place="rightColumnFooter"] p, #bxStylistFooter [data-bx-block-editor-place="rightColumnFooter"] a:not(.bxBlockContentButton), #bxStylistFooter [data-bx-block-editor-place="rightColumnFooter"] span, #bxStylistFooter [data-bx-block-editor-place="rightColumnFooter"] table{
				text-align: right;
			}

			#bxStylistFooter [data-bx-block-editor-place="footer"] p, #bxStylistFooter [data-bx-block-editor-place="footer"] a:not(.bxBlockContentButton), #bxStylistFooter [data-bx-block-editor-place="footer"] span, #bxStylistFooter [data-bx-block-editor-place="footer"] table{
				text-align: center;
			}

			/* padding */
			.bxBlockPadding{
				padding: 16px 32px;
			}			
			@media (max-width: 599px){
				.bxBlockPadding{
					padding-top: 10px !important;
					padding-bottom: 10px !important;
					padding-left: 20px !important;
					padding-right: 20px !important;
				}
			}
			.bxBlockPadding:empty{
				display: none;
			}

			/* columns */
			.bxBlockInnText table[width="210"]{
				max-width: 100%;
				width: 100%;
			}
			@media (min-width: 600px){
				.bxBlockInnText table[width="210"]{
					max-width: 33.33% !important;
					width: 33.33% !important;
				}
			}
			.bxBlockInnText table[width="318"]{
				max-width: 100%;
				width: 100%;
			}
			@media (min-width: 600px){
				.bxBlockInnText table[width="318"]{
					max-width: 50% !important;
					width: 50% !important;
				}
			}

			/* image */
			.bxImage{
				max-width: 100%;
				height: auto;
			}
			#bxStylistBody [data-bx-block-editor-place="body"] .mail-body-image .bxBlockPadding{
				padding-left: 0 !important;
				padding-right: 0 !important;
			}

			/* logo */
			.mail-logo .bxBlockPadding.bxBlockContentImage{
			}
			a.link_img_logo{
				display: inline-block;
				zoom: 1;
				vertical-align: middle;
				margin: 0;
				background: <?=$logoBgColor?>;
				max-width: 170px;
				height: 50px;
			}
			a.link_img_logo img{
				display: block;
				max-height: 100%;
				max-width: 100%;
			}

			/* button */
			.bxButton{
				display: inline-block;
				line-height: 22px;
				color: #fff;
				padding: 7px 15px ;
				text-decoration: none;
				text-align: center;
			}
			.bxBlockContentButtonEdge{
				text-align: center;
				overflow: hidden;
			}
			.bxBlockContentButtonEdge:hover{
				opacity: 0.85;
				cursor: pointer;
			}
			.bxBlockContentButton{
				background-color: <?=$baseColor?>;
				text-align: center;
				display: inline-block;
				width: 100%;
				line-height: 20px;
				color: #fff;
				text-decoration: none;
				font-size: 16px;
				min-height: 47px;
				padding: 13px 24px;
				vertical-align: middle;
				box-sizing: border-box;
			}
			.block-button{
				background-color: <?=$baseColor?>;
				border-radius: 7px;
				overflow: hidden;
			}
			.block-button a{
				text-align: center;
				display: inline-block;
				width: 100%;
				line-height: 20px;
				color: #fff;
				text-decoration: none;
				font-size: 16px;
				min-height: 47px;
				padding: 13px 24px;
				vertical-align: middle;
				box-sizing: border-box;
			}
			.block-button:hover {
				opacity: 0.85;
			}
			.mail-round-border + .block-button{
				margin-top: 24px !important;
			}

			/* line */
			.bxBlockLine .bxBlockContentLine{
				display: block;
				width: 100%;
			}

			/* socials */
			.mail-socials .bxBlockPadding{
				padding-left: 30px;
				padding-right: 30px;
			}
			@media (max-width: 599px){
				.mail-socials .bxBlockPadding{
					padding-left: 18px!important;
					padding-right: 18px!important;
				}
			}
			.mail-socials-items {
				display: inline-block;
				text-align: center;
				background: none;
				margin: 0 auto;
				background-color: transparent;
				vertical-align: top;
			}
			.mail-socials .bxBlockInnText .mail-socials-item{
				width: initial !important;
				max-width: initial !important;
			}
			.mail-socials-item .bxBlockContentSocial{
				width: 40px;
				height: 40px;
				background-color: #ffffff;				
				border-radius: 8px;
				display: inline-block;
				vertical-align: middle;
				text-align: center;
				margin-left: 2px !important;
				margin-right: 2px !important;
				padding: 10px;
				box-sizing: border-box;
			}
			#bxStylistHeader .mail-socials-item .bxBlockContentSocial,
			#bxStylistBody .mail-socials-item .bxBlockContentSocial,
			#bxStylistFooter .mail-socials-item .bxBlockContentSocial{
				font-size: 0px;
			}
			.mail-socials-item .bxBlockContentSocial:hover{
				opacity: 0.85;
			}
			.mail-socials-item .bxBlockContentSocial span {
				width: 20px;
				height: 20px;
				box-sizing: border-box;
				display: inline-block;
			}

			.mail-header{
				background-color: transparent;
			}
			.mail-footer{
				background-color: transparent;
			}

			/* component */
			.bx-editor-block.bx-type-component{
				padding: 16px 32px !important;
			}

			/* wrapper */
			.mail-wrapper-block{
				overflow: hidden;
				position: relative;
				font-size: 16px;
				line-height: 22px;
			}

			/* wrapper title */
			.mail-wrapper-block-title{
				font-size: 20px;
				line-height: 26px;
				font-weight: 400;
				padding-bottom: 20px;
			}
			.mail-wrapper-block-subtitle{
				padding: 32px 0 20px;
				font-size: 20px;
				line-height: 26px;
				font-weight: 400;
			}

			/* wrapper note */
			.mail-wrapper-block-note{
				padding-bottom: 20px;
			}

			/* round border */
			.mail-round-border{
				border: 1px solid #ededed;
				border-radius: 8px;
				overflow: hidden;
			}

			/* custom */
			.mail-body-bottom .bxBlockPadding{
				padding-top: 8px;
				padding-bottom: 8px;
			}
			.mail-copyright .bxBlockPadding{
				padding-top: 13px;
			}
		</style>

		<style type="text/css">
			/* cart */
			.cart-items{
				padding: 0;
				min-width: 100%;
				table-layout: fixed;
			}
			.cart-item{
				border-top: 1px solid #ededed;
				padding: 19px 23px;
				display: block;
				border-radius: 0;
				overflow: hidden;
			}
			.cart-item--first{
				border-top: none;
			}
			@media (max-width: 599px){
				.cart-item{
					padding: 16px !important;
					display: block !important;
				}
			}
			.cart-item-name--normal{
				display: block;
				margin-top: 1px;
			}
			@media (max-width: 599px){
				.cart-item-name--normal{
					display: none !important;
				}
			}
			.cart-item-name--mobile{
				display: none;
			}
			@media (max-width: 599px){
				.cart-item-name--mobile{
					display: block !important;
				}
			}
			.cart-item-img{
				width: 75px;
				height: 75px;
				margin-right: 28px;
				float: left;
			}
			@media (max-width: 599px){
				.cart-item-img{
					width: 40px !important;
					height: 40px !important;
					margin-right: 16px !important;
				}
			}
			.cart-item-props{
				padding: 0;
				font-size: 14px;
				line-height: 18px;
			}
			.cart-item-name + .cart-item-props {
				padding-top: 8px !important;
			}
			@media (max-width: 599px){
				.cart-item-props{
					padding-bottom: 12px !important;
				}
			}
			.cart-item-prop{
				padding-top: 4px;
			}
			.cart-item-prop-name{
				color: #999999;
			}
			.cart-item-prop-value{
				color: #555555;
			}
			.cart-item-sum{
				white-space: nowrap;
				margin-bottom: 1px;
				color: #222222;
			}
			@media (max-width: 599px){
				.cart-item-sum{
					margin-bottom: 0 !important;
					margin-right: 8px;
					display: inline-block;
					vertical-align: bottom;
				}
			}
			.cart-item-old-sum{
				text-decoration: line-through;
				color: #555555;
				font-size: 12px;
				line-height: 18px;
				white-space: nowrap;
				margin-bottom: 4px;
			}
			@media (max-width: 599px){
				.cart-item-old-sum{
					margin-bottom: 0 !important;
					margin-right: 8px;
					margin-left: -4px;
					display: inline-block;
					vertical-align: bottom;
				}
			}
			.cart-item-quantity{
				color: #222222;
				font-size: 12px;
				line-height: 18px;
			}
			@media (max-width: 599px){
				.cart-item-quantity{
					display: inline-block;
					vertical-align: bottom;
				}
			}
			.cart-item-column{
				padding-top: 3px;
				float: left;
				max-width: 265px;
			}
			@media (max-width: 599px){
				.cart-item-column{
					padding-top: 0 !important;
					float: none !important;
				}
			}
			.cart-item-column--first{
				padding-top: 0;
			}
			.cart-item-column--last{
				text-align: right;
				padding-left: 28px;
				float: right;
			}
			@media (max-width: 599px){
				.cart-item-column--last{
					margin-left: 0 !important;
					text-align: left !important;
					padding-left: 0 !important;
					float: none !important;
				}
			}
			.cart-item-itog{
				width: 100%;
				overflow: hidden;
			}
			.cart-item-itog-label{
				display: inline-block;
				vertical-align: top;
				text-align: right;
			}
			.cart-item-itog-sum{
				text-align: right;
				float: right;
			}
			.block-button--cart-review--mobile{
				display: none;
			}
			@media (max-width: 599px){
				.block-button--cart-review--mobile{
					display: block !important;
					margin: 12px 0 0;
				}
			}
			.block-button--cart-review--normal{
				margin: 12px 0 0;
				display: inline-block;
			}
			@media (max-width: 599px){
				.block-button--cart-review--normal{
					display: none !important;
				}
			}
			.block-button--cart-review a{
				line-height: 18px;
				font-size: 14px;
				min-height: 35px;
				padding: 7px 24px;
				width: 100%;
			}
		</style>
		
		<style type="text/css">
			/* coupon */
			.coupon-block{
				margin: 0 0 16px;
				text-align: center;
				min-height: 47px;
				display: block;
				padding: 13px 24px;
				background-color: <?=Template::hex2rgb($baseColor, 0.1)?>;
				border-radius: 7px;
				overflow: hidden;
				box-sizing: border-box;
			}
			.coupon-title{
				font-size: 16px;
				line-height: 20px;
				color: #222222;
				text-align: center;
				text-decoration: none;
				position: relative;
			}
			.coupon-value{
				font-size: 16px;
				line-height: 20px;
				color: #222222;
				font-weight: 600;
				position: relative;
				white-space: nowrap;
			}
			.block-button--coupon{
				margin: 0 0 16px;
			}
			.coupon-limit{
				font-size: 14px;
				line-height: 22px;
				color: #555555;
				margin-top: 4px;
				text-align: center;
			}
		</style>

		<style type="text/css">
			/* products */
			.mail-wrapper-block--products{
				overflow: visible;
			}
			.mail-wrapper-block-title--products-block{
				padding-bottom: 12px;
			}
			.mail-wrapper-block-note--products-block{
				padding-top: 8px;
				padding-bottom: 12px;
			}
			.products-items{
				display: block;
				font-size: 0;
			}
			.products-item{
				padding: 8px 0 8px 8px;
				box-sizing: border-box;
				width: 50%;
				display: inline-block;
				vertical-align: top;
			}
			.products-item-odd{
				padding: 8px 8px 8px 0;
			}
			@media (max-width: 599px){
				.products-item{
					width: 100% !important;
					padding-left: 0 !important;
					padding-right: 0 !important;
				}
			}
			.products-item .mail-round-border{
				height: 100%;
			}
			.products-item-inner{
				padding: 24px;
				border-radius: 0;
				box-sizing: border-box;
			}
			.products-item-name{
				font-size: 16px;
				line-height: 21px;
				height: 21px;
				display: -webkit-box;
				-webkit-line-clamp: 1;
				-webkit-box-orient: vertical;  
				overflow: hidden;
			}
			@media (max-width: 599px){
				.products-item-name{
					display: block !important;
					height: auto !important;
					overflow: visible !important;
				}
			}
			.products-item-img{
				width: 80px;
				height: 80px;
				margin: 0 0 18px 0;
			}
			.products-item-price{
				font-size: 18px;
				line-height: 24px;
				color: #222222;
				white-space: nowrap;
				margin: 0 0 1px 0;
				display: inline-block;
				vertical-align: bottom;
			}
			.products-item-old-price{
				text-decoration: line-through;
				color: #555555;
				font-size: 12px;
				line-height: 18px;
				white-space: nowrap;
				margin: 0 0 1px 4px;
				display: inline-block;
				vertical-align: bottom;
			}
			@media (max-width: 599px){
				.products-item-old-price{
					display: block !important;
					margin-bottom: 4px !important;
					margin-left: 0px !important;
				}
			}
			.products-item-line{
				min-height: 25px;
			}
			.block-button--products{
				margin-top: 24px !important;
			}
		</style>

		<style type="text/css">
			/* order */
			.order-part-inner{
				padding: 6px 24px 24px;
				color: #222222;
			}
		</style>

		<style type="text/css">
			/* remove bx-editor block`s padding */
			.bx-editor-block {
				padding: 0 !important;
			}
			.bx-editor-block .bx-block-inside:hover,
			.bx-editor-block.bx-editor-block-current-edit .bx-block-inside{
				min-height:auto !important;
			}
			.bx-editor-block .bx-block-inside:hover,
			.bx-editor-block.bx-editor-block-current-edit .bx-block-inside {
				outline: none !important;
				position: relative;
			}
			.bx-editor-block .bx-block-inside:hover:after,
			.bx-editor-block.bx-editor-block-current-edit .bx-block-inside::after {
				position: absolute;
				z-index: 1;
				border: 1px solid #283b4e;
				display: block;
				content: "";
				top: 0;
				bottom: 0;
				left: 0;
				right: 0;
			}

			body .bx-editor-place{
				margin: 0 !important;
				border: 1px dashed rgb(125, 125, 125)!important;
				outline: none !important;
			}

			/* invisible cells */
			table.mail-grid-cell.mail-grid-cell--invisible .bx-editor-place:not(.bx-dd-start) {
				opacity: 0.3;
			}
		</style>

		<style data-bx-stylist-container="item" type="text/css">
			/* page */
			body, #bxStylistPage{
				background: #f8f8f8;
			}
			body h1, #bxStylistPage h1{
				font-family: Arial, Helvetica;
				color: #222222 !important;
				font-size: 40px;
				line-height: 50px;
				font-style: normal !important;
				font-weight: bold !important;
				text-align: left !important;
			}
			body h2, #bxStylistPage h2{
				font-family: Arial, Helvetica;
				color: #222222 !important;
				font-size: 32px;
				line-height: 42px;
				font-style: normal !important;
				font-weight: bold !important;
				text-align: left !important;
			}
			body h3, #bxStylistPage h3{
				font-family: Arial, Helvetica;
				color: #222222 !important;
				font-size: 18px;
				line-height: 28px;
				font-style: normal !important;
				font-weight: bold !important;
				text-align: left !important;
			}
			body h4, #bxStylistPage h4{
				font-family: Arial, Helvetica;
				color: #222222 !important;
				font-size: 16px;
				line-height: 26px;
				font-style: normal !important;
				font-weight: bold !important;
				text-align: left !important;
			}

			/* header */
			#bxStylistHeader{
				font-family: Arial, Helvetica;
				background: none;
				font-size: 14px;
				line-height: 22px;
				padding-top: 20px;
				padding-bottom: 10px;
			}
			#bxStylistHeader .bxBlockContentText, #bxStylistHeader .bxBlockContentText p, #bxStylistHeader .bxBlockContentSocial, #bxStylistHeader .bxBlockContentSocial p, #bxStylistHeader .bxBlockContentBoxedText, #bxStylistHeader .bxBlockContentBoxedText p{
				font-family: Arial, Helvetica;
				color: #999999;
				font-weight: 400;
				font-size: 14px;
				line-height: 22px;
				margin: 0;
			}
			#bxStylistHeader .bxBlockSocial a, #bxStylistHeader .bxBlockContentText a, #bxStylistHeader .bxBlockContentBoxedText a{
				font-family: Arial, Helvetica;
				font-weight: 400;
				font-size: 14px;
				line-height: 26px;
				text-decoration: none;
			}

			/* body */
			#bxStylistBody{
				font-family: Arial, Helvetica;
				color: #222222;
				font-size: 16px;
				line-height: 22px;
				background: #ffffff;
				padding-top: 15px;
				padding-bottom: 15px;
				padding-right: 0px;
				padding-left: 0px;
				border-radius: <?=$outerBorderRadius?>;
				overflow: hidden;
			}
			#bxStylistBody .bxBlockContentText, #bxStylistBody .bxBlockContentText p, #bxStylistBody .bxBlockContentSocial, #bxStylistBody .bxBlockContentSocial p, #bxStylistBody .bxBlockContentBoxedText, #bxStylistBody .bxBlockContentBoxedText p{
				font-family: Arial, Helvetica;
				color: #222222;
				font-weight: 400;
				font-size: 16px;
				line-height: 22px;
				margin: 0;
			}
			#bxStylistBody .bxBlockSocial a, #bxStylistBody .bxBlockContentText a, #bxStylistBody .bxBlockContentBoxedText a{
				font-family: Arial, Helvetica;
				font-weight: 400;
				font-size: 16px;
				line-height: 22px;
				text-decoration: none;
			}

			/* footer */
			#bxStylistFooter{
				font-family: Arial, Helvetica;
				background: none;
				font-size: 14px;
				line-height: 22px;
				padding-top: 10px;
				padding-bottom: 20px;
			}
			#bxStylistFooter .bxBlockContentText, #bxStylistFooter .bxBlockContentText p, #bxStylistFooter .bxBlockContentSocial, #bxStylistFooter .bxBlockContentSocial p, #bxStylistFooter .bxBlockContentBoxedText, #bxStylistFooter .bxBlockContentBoxedText p{
				font-family: Arial, Helvetica;
				color: #999999;
				font-weight: 400;
				font-size: 14px;
				line-height: 22px;
				margin: 0;
			}
			#bxStylistFooter .bxBlockSocial a, #bxStylistFooter .bxBlockContentText a, #bxStylistFooter .bxBlockContentBoxedText a{
				font-family: Arial, Helvetica;
				font-weight: 400;
				font-size: 14px;
				line-height: 22px;
				text-decoration: none;
			}
		</style>

		<style type="text/css">
			/* This editor is ...! Do not merge next styles with bx data-bx-stylist-container ! */
			body h2{
			}
			@media (max-width: 599px) {
				body h2{
					font-size: 24px !important;
					line-height: 32px !important;
				}
			}
			body h3{
			}
			@media (max-width: 599px) {
				body h3{
					font-size: 14px !important;
					line-height: 24px !important;
				}
			}
			body h4{
			}
			@media (max-width: 599px) {
				body h4{
					font-size: 12px !important;
					line-height: 22px !important;
				}
			}
			#bxStylistHeader{
			}
			@media (max-width: 599px) {
				#bxStylistHeader{
					padding-top: 15px !important;
					padding-bottom: 25px !important;
				}
			}
			#bxStylistBody{
			}
			@media (max-width: 599px) {
				#bxStylistBody{
					padding-top: 10px !important;
					padding-bottom: 10px !important;
				}
			}
			#bxStylistFooter{
			}
			@media (max-width: 599px) {
				#bxStylistFooter{
					padding-top: 25px !important;
					padding-bottom: 15px !important;
				}
			}
		</style>
	</head>
	<body>
		<div class="mail-wrap">
			<center>
				<!-- top content -->
				<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
				<tbody>
					<tr>
						<td id="bxStylistHeader">
							<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
							<tbody>
								<tr>
									<td class="mail-header" style="background-color:transparent;">
										<div style="width:50% !important;float:left;vertical-align:middle;">
											<table class="mail-grid-cell mail-grid-cell--invisible" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
											<tbody>
												<tr>
													<td data-bx-block-editor-place="leftColumnHeader">
													</td>
												</tr>
											</tbody>
											</table>
										</div>
										
										<div style="width:50% !important;float:right;vertical-align:middle;">
											<table class="mail-grid-cell mail-grid-cell--invisible" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
											<tbody>
												<tr>
													<td data-bx-block-editor-place="rightColumnHeader">
													</td>
												</tr>
											</tbody>
											</table>
										</div>

										<div>
											<table class="mail-grid-cell mail-grid-cell--invisible" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
											<tbody>
												<tr>
													<td data-bx-block-editor-place="header">
														<div data-bx-block-editor-block-type="image" draggable="true">
															<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockImage mail-block mail-logo">
															<tbody class="bxBlockOut">
																<tr>
																	<td valign="top" class="bxBlockInn bxBlockInnImage">
																		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
																		<tbody>
																			<tr>
																				<td valign="top" class="bxBlockPadding bxBlockContentImage">
																					<a align="center" href="<?=$siteAddressFull?>" class="link_img_logo">
																						<img align="center" data-bx-editor-def-image="0" src="<?=$logoSrc?>" class="bxImage">
																					</a>
																				</td>
																			</tr>
																		</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
															</table>
														</div>
													</td>
												</tr>
											</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
							</table>
						</td>
					</tr>
				</tbody>
				</table>
				<!-- /top content -->

				<!-- middle content -->
				<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
					<tr>
						<td id="bxStylistBody">
							<!-- content -->
							%TEMPLATE_CONTENT%
							<!-- /content -->
						</td>
					</tr>
				</table>
				<!-- /middle content -->

				<!-- bottom content -->				
				<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
				<tbody>
					<tr>
						<td id="bxStylistFooter">
							<table class="mail-grid" width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
							<tbody>
								<tr>
									<td class="mail-footer" style="background-color:transparent;">
										<div>
											<table class="mail-grid-cell mail-grid-cell--invisible" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
											<tbody>
												<tr>
													<td data-bx-block-editor-place="footer">
														<div data-bx-block-editor-block-type="text">
															<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockText mail-block mail-socials">
															<tbody class="bxBlockOut">
																<tr>
																	<td valign="top" class="bxBlockInn bxBlockInnText">
																		<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
																		<tbody>
																			<tr>
																				<td valign="top" class="bxBlockPadding bxBlockContentText">
																					<div class="mail-socials-items">
																						<?if ($socialVk):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-vk">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialVk?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_VK'))?>"><img src="<?=$imgPath?>/socials/vk.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_VK')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialFacebook):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-facebook">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialFacebook?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_FACEBOOK'))?>"><img src="<?=$imgPath?>/socials/fb.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_FACEBOOK')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialTwitter):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-twitter">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialTwitter?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_TWITTER'))?>"><img src="<?=$imgPath?>/socials/twitter.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_TWITTER')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialInstagram):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-instagram">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialInstagram?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_INSTAGRAM'))?>"><img src="<?=$imgPath?>/socials/instagram.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_INSTAGRAM')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialTelegram):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-telegram">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialTelegram?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_TELEGRAM'))?>"><img src="<?=$imgPath?>/socials/telegram.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_TELEGRAM')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialYoutube):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-youtube">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialYoutube?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_YOUTUBE'))?>"><img src="<?=$imgPath?>/socials/youtube.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_YOUTUBE')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialOdnoklassniki):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-odnoklassniki">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialOdnoklassniki?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_ODNOKLASSNIKI'))?>"><img src="<?=$imgPath?>/socials/ok.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_ODNOKLASSNIKI')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialMail):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-mail">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialMail?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_MAIL'))?>"><img src="<?=$imgPath?>/socials/mail.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_MAIL')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialTikTok):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-tiktok">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialTikTok?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_TIKTOK'))?>"><img src="<?=$imgPath?>/socials/tiktok.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_TIKTOK')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialViber):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-viber">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialViber?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_VIBER'))?>"><img src="<?=$imgPath?>/socials/viber.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_VIBER')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialZen):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-zen">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialZen?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_ZEN'))?>"><img src="<?=$imgPath?>/socials/zen.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_ZEN')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialPinterest):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-pinterest">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialPinterest?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_PINTEREST'))?>"><img src="<?=$imgPath?>/socials/pinterest.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_PINTEREST')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialSnapchat):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-snapchat">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialSnapchat?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_SNAPCHAT'))?>"><img src="<?=$imgPath?>/socials/snapchat.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_SNAPCHAT')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialLinkedin):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-linkedin">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialLinkedin?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_LINKEDIN'))?>"><img src="<?=$imgPath?>/socials/linkedin.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_LINKEDIN')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>

																						<?if ($socialAsproLink):?>
																							<table align="left" border="0" cellpadding="0" cellspacing="0" class="mail-socials-item mail-socials-asprolink">
																							<tbody>
																								<tr>
																									<td valign="top" class="" style="font-size: 12px;">
																										<a class="bxBlockContentSocial" href="<?=$socialAsproLink?>" target="_blank" style="color: #222222;" title="<?=htmlspecialcharsbx(Loc::getMessage('SOCIAL_ASPRO_LINK'))?>"><img src="<?=$imgPath?>/socials/asprolink.png" alt="" title="" /><?=Loc::getMessage('SOCIAL_ASPRO_LINK')?></a>
																									</td>
																								</tr>
																							</tbody>
																							</table>
																						<?endif;?>
																					</div>
																				</td>
																			</tr>
																		</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
															</table>
														</div>

														<div data-bx-block-editor-block-type="text">
															<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockText mail-block mail-unsubscribe">
															<tbody class="bxBlockOut">
																<tr>
																	<td valign="top" class="bxBlockInn bxBlockInnText">
																		<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
																		<tbody>
																			<tr>
																				<td valign="top" class="bxBlockPadding bxBlockContentText">
																					<p><?=Loc::getMessage('UNSUB_TEXT', array('#SITE_ADDRESS#' => $siteAddressFull))?></p>
																					<p><?=Loc::getMessage('UNSUB_LINK_TEXT')?></p>
																				</td>
																			</tr>
																		</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
															</table>
														</div>

														<div data-bx-block-editor-block-type="text">
															<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bxBlockText mail-block mail-copyright">
															<tbody class="bxBlockOut">
																<tr>
																	<td valign="top" class="bxBlockInn bxBlockInnText">
																		<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">
																		<tbody>
																			<tr>
																				<td valign="top" class="bxBlockPadding bxBlockContentText">
																					<div><?=$copyrightHtml?></div>
																				</td>
																			</tr>
																		</tbody>
																		</table>
																	</td>
																</tr>
															</tbody>
															</table>
														</div>
													</td>
												</tr>
											</tbody>
											</table>
										</div>

										<div style="width:50% !important;float:left;vertical-align:middle;">
											<table class="mail-grid-cell mail-grid-cell--invisible" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
											<tbody>
												<tr>
													<td data-bx-block-editor-place="leftColumnFooter">
													</td>
												</tr>
											</tbody>
											</table>
										</div>
										
										<div style="width:50% !important;float:right;vertical-align:middle;">
											<table class="mail-grid-cell mail-grid-cell--invisible" width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
											<tbody>
												<tr>
													<td data-bx-block-editor-place="rightColumnFooter">
													</td>
												</tr>
											</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
							</table>
						</td>
					</tr>
				</tbody>
				</table>							
				<!-- /bottom content -->
			</center>
		</div>
	</body>
</html>