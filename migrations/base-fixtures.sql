SET FOREIGN_KEY_CHECKS = 0;

insert  into `block_template`(`id`,`for_type_id`,`name`,`view_script`,`description`) values (2,2,'html','_html',NULL),(5,5,'image','_image',NULL);
insert  into `block_type`(`id`,`name`,`icon`,`description`) values (2,'html','/images/icons/block.png',NULL),(5,'image','/images/icons/block.png',NULL);
insert  into `helpdesk_ticket_status`(`text`) values ('OPEN'),('RESOLVED');
insert  into `item_teaser_template`(`id`,`for_type_id`,`name`,`view_script`,`description`) values (4,4,'auto','auto-body-teaser','Automatically generates teaser from body');
insert  into `item_template`(`id`,`for_type_id`,`name`,`view_script`,`description`) values (10,4,'three-column','article-3col','Body uses large middle section, navigation on left and blocks in right column'),(11,4,'two-column','article-2col','Body uses large left section and blocks in right column');
insert  into `item_template_block`(`id`,`template_id`,`name`,`admin_label`,`sequence`) values (26,10,'leftColumn','Left column',1),(27,10,'mainImage','Main image',0),(28,10,'lowerPromo','Lower promo',0),(29,10,'rightColumn','Right column',1),(30,11,'rightColumn','Right column',1);
insert  into `item_type`(`id`,`name`,`icon`,`description`,`multiple_parts`) values (4,'article','/images/icons/item.png','Single part article',0);
insert  into `menu`(`id`,`name`,`primary`) values (1,'main',1);
insert  into `module`(`id`,`name`,`enabled`,`route_controller`,`route_action`) values (2,'item',1,'item','view'),(9,'search',1,'search',NULL);
insert  into `module_page`(`id`,`module_id`,`name`) values (8,9,'simple');
insert  into `module_page_block`(`id`,`module_page_id`,`name`,`admin_label`,`sequence`) values (1,8,'rightColumn','Right column',1);
insert  into `role`(`id`,`name`) values (1,'admin'),(2,'author'),(3,'publisher'),(7,'asset-manager'),(9,'helpdesk-user'),(10,'helpdesk-manager');
insert  into `status`(`text`) values ('DELETED'),('DRAFT'),('PUBLISHED'),('REVISION'),('ROLLBACK');
insert  into `user`(`id`,`username`,`email`,`password`) values (1,'admin','root@localhost','5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8');
insert  into `user_role`(`id`,`user_id`,`role_id`) values (21,1,1);
insert  into `workflow_stage`(`text`) values ('AUTHORING'),('PUBLISHING'),('REJECTED');

SET FOREIGN_KEY_CHECKS = 1;
