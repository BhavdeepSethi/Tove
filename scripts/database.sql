CREATE TABLE `users` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`emailId` varchar(100) NOT NULL,
`userKey` varchar(100) NOT NULL,
`status` tinyint(1) DEFAULT 0,
`createdTime` DATETIME DEFAULT CURRENT_TIMESTAMP,
`updatedTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`id`),
UNIQUE KEY(`emailId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User data';


CREATE TABLE `userWishlist` (
`userId` int(11) NOT NULL AUTO_INCREMENT,
`mId` varchar(100) NOT NULL,
`status` tinyint(1) DEFAULT 1,
`createdTime` DATETIME DEFAULT CURRENT_TIMESTAMP,
`updatedTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`userId`,`mId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User Wishlist data';


CREATE TABLE `userRating` (
`userId` int(11) NOT NULL AUTO_INCREMENT,
`mId` varchar(100) NOT NULL,
`rating` int(1) NOT NULL DEFAULT 0,
`status` tinyint(1) DEFAULT 1,
`createdTime` DATETIME DEFAULT CURRENT_TIMESTAMP,
`updatedTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (`userId`,`mId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='User Rating data';


CREATE TABLE `movieList` (
  `mId` varchar(15) NOT NULL,
  `status` tinyint(1) DEFAULT 0,
  `createdTime` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updatedTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`mId`),
  KEY(`status`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='movie process list';




CREATE TABLE `movie` (
  `mId` varchar(15) NOT NULL,
  `title` varchar(200) NOT NULL,
  `year` int(5) NOT NULL,
  `tagline` varchar(200),
  `plot` varchar(2000),
  `image` varchar(500),
  `runtime` int(4),
  `contentRating` varchar(3),
  `createdTime` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updatedTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`mId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='movie data';


insert into movie (mId, title, year, tagLine, plot, image, runtime, contentRating)values ('tt0068646','The Godfather','1972',"An offer you can't refuse.","The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.",'http://ia.media-imdb.com/images/M/MV5BMjEyMjcyNDI4MF5BMl5BanBnXkFtZTcwMDA5Mzg3OA@@._V1._SY200.jpg',175, 'R');

insert into movie (mId, title, year, tagLine, plot, image, runtime, contentRating)values ('tt2267998','Gone Girl','2014',"You don't know what you've got 'til it's...","With his wife's disappearance having become the focus of an intense media circus, a man sees the spotlight turned on him when it's suspected that he may not be innocent.",'http://ia.media-imdb.com/images/M/MV5BMTk0MDQ3MzAzOV5BMl5BanBnXkFtZTgwNzU1NzE3MjE@._V1._SY200.jpg',149, 'R');




//Genre table

CREATE TABLE `movieGenre` (
  `mId` varchar(15) NOT NULL,
  `genre` varchar(30) NOT NULL,
  `createdTime` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updatedTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`mId`,`genre`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='movie genre mapping';

insert into movieGenre(mid,genre) values ('tt0068646', 'crime');
insert into movieGenre(mid,genre) values ('tt0068646', 'drama');


insert into movieGenre(mid,genre) values ('tt2267998', 'drama');
insert into movieGenre(mid,genre) values ('tt2267998', 'mystery');
insert into movieGenre(mid,genre) values ('tt2267998', 'thriller');



//Cast Table
CREATE TABLE `movieCast` (
  `mId` varchar(15) NOT NULL,
  `castType` varchar(50) NOT NULL,
  `castName` varchar(100) NOT NULL,
  `createdTime` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updatedTime` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY(`mId`,`castType`,`castName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='movie cast mapping';

insert into movieCast(mid,castType,castName) values ('tt0068646', 'cast','marlon brando');
insert into movieCast(mid,castType,castName) values ('tt0068646', 'cast','al pacino');
insert into movieCast(mid,castType,castName) values ('tt0068646', 'cast','james caan');
insert into movieCast(mid,castType,castName) values ('tt0068646', 'writer','mario puzo');
insert into movieCast(mid,castType,castName) values ('tt0068646', 'writer','francis ford coppola');
insert into movieCast(mid,castType,castName) values ('tt0068646', 'director','francis ford coppola');


insert into movieCast(mid,castType,castName) values ('tt2267998', 'cast','Ben Affleck');
insert into movieCast(mid,castType,castName) values ('tt2267998', 'cast','Rosamund Pike');
insert into movieCast(mid,castType,castName) values ('tt2267998', 'cast','neil patrick harris');
insert into movieCast(mid,castType,castName) values ('tt2267998', 'writer','gillian flynn');
insert into movieCast(mid,castType,castName) values ('tt2267998', 'director','david fincher');

//select m.*, GROUP_CONCAT(mg.genre) from movie m LEFT JOIN movieGenre mg ON m.mId=mg.mId


//imdb recommended
