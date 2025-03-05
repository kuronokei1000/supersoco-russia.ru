create table if not exists b_aspro_lite_ozon_goods
(
	ID int not null auto_increment,
	CLIENT_ID int not null,
	STEP int not null,
	VALUE longtext,
	PRIMARY KEY (ID)
);