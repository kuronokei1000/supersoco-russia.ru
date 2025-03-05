create table if not exists b_aspro_lite_ozon_prop_values
(
	ID int not null auto_increment,
	CATEGORY_ID int not null,
	PROPERTY_ID int not null,
	STEP int not null,
	VALUE longtext,
	PRIMARY KEY (ID)
);