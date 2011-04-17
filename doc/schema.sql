CREATE TABLE campaign (id serial unique, template varchar(32),goal int,pledge_deadline date,payment_deadline date);NOTICE:  CREATE TABLE will create implicit sequence "campaign_id_seq" for serial column "campaign.id"
CREATE TABLE pledge (id serial unique,campain_id int REFERENCES campaign (id),name text,street_address1 text,street_address2 text,zipcode text,city text,country text,email text,remark text,note text,confirm_hash varchar(64),payment_hash varchar(64),confirmed timestamp);
CREATE TABLE payment (id serial unique,campaign_id REFERENCES campaign id,pledge_id REFERENCES pledge_id,amount int,note text);
