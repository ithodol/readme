drop database if exists readme;
create database readme;
use readme;

# 사용자
create table user( 
	uno int unsigned auto_increment, -- 식별번호
    uid varchar(20) NOT NULL UNIQUE, -- 아이디
    upwd varchar(30) NOT NULL, -- 비밀번호
    uname varchar(20) NOT NULL, -- 이름
	uphone varchar(13) NOT NULL, -- 연락처
    ustate boolean default 0, -- 대출가능 여부 0가능 1불가능
	constraint primary key (uno)
);


INSERT INTO user (uid, upwd, uname, uphone, ustate) VALUES
('user1', '1234', '김한주', '010-1111-1111', 0),
('user2', '1234', '김춘추', '010-2222-2222', 0),
('user3', '1234', '김건재', '010-3333-3333', 1),
('user4', '1234', '최웅희', '010-4444-4444', 0),
('user5', '1234', '이혁재', '010-5555-5555', 0),
('user6', '1234', '이동해', '010-6666-6666', 0),
('user7', '1234', '이병건', '010-7777-7777', 1),
('user8', '1234', '이세화', '010-8888-8888', 0),
('user9', '1234', '박정민', '010-9999-9999', 0),
('user10','1234', '김정환', '010-0000-0000', 0);

# 관리자
create table admin(
	adno int unsigned auto_increment,
    adid varchar(20) NOT NULL UNIQUE,
    adpwd varchar(30) NOT NULL,
    adname varchar(20) NOT NULL,
    adphone varchar(13) NOT NULL,
    constraint primary key (adno)
);

INSERT INTO admin (adid, adpwd, adname, adphone) VALUES
('admin1', 'admin123', '관리자', '010-1111-9999');

# 위치
CREATE TABLE slot (
    sno INT unsigned AUTO_INCREMENT,  -- 위치 번호
    srow VARCHAR(2) NOT NULL,    
    scol INT NOT NULL,             
    constraint primary key (sno)
);

INSERT INTO slot (srow, scol) VALUES
('A', 1),
('A', 2),
('B', 1),
('B', 2);


# 도서
CREATE TABLE book (
    bno INT unsigned AUTO_INCREMENT,       -- 도서 번호
    btitle VARCHAR(100) NOT NULL,             -- 제목
    briter VARCHAR(50) NOT NULL,              -- 저자
    bpub VARCHAR(50) NOT NULL,                -- 출판사
    bstate BOOLEAN DEFAULT 0,                 -- 대출 상태 (0: 가능, 1: 대출 중)
    bimg VARCHAR(100),                        -- 도서 이미지 파일명 or 경로
    sno INT unsigned NOT NULL,                         -- 위치 번호 (외래키)
    constraint primary key (bno),
    constraint foreign key(sno) references slot(sno) on update cascade on delete cascade
);

INSERT INTO book (btitle, briter, bpub, bstate, bimg, sno) VALUES
('파과', '구병모', '창비', 0, 'pagua.jpg', 1),
('종의 기원', '정유정', '은행나무', 1, 'origin.jpg', 2),
('완전한 행복', '정유정', '은행나무', 0, 'happiness.jpg', 3),
('살육에 이르는 병', '아비코 다케마루', '시공사', 0, 'murder.jpg', 4),
('도롱뇽의 49재', '아사히나 아키', '시공사', 0, 'salamander.jpg', 1),
('부디 당신이 타락하기를', '무경', '나비클럽', 1, 'fall.jpg', 2),
('혼모노', '성해나', '창비', 0, 'Honmono.jpg', 3),
('해리포터와 마법사의 돌', 'J.K. 롤링', '문학수첩', 1, 'hp1.jpg', 4),
('1984', '조지 오웰', '민음사', 0, '1984.jpg', 1),
('데미안', '헤르만 헤세', '민음사', 0, 'demian.jpg', 2);


# 대출
CREATE TABLE loan (
    lno INT unsigned AUTO_INCREMENT,    -- 대출 번호
    ldate DATE NOT NULL,                    -- 대출 일자
    lddate DATE NOT NULL,                   -- 반납 예정일
    lrdate DATE,                           -- 반납일 (NULL 가능)
    lstate BOOLEAN NOT NULL DEFAULT 0,     -- 반납 상태 (0: 대출중, 1: 반납됨)
    uno INT unsigned NOT NULL,                      -- 사용자 번호 (외래키)
    bno INT unsigned NOT NULL,                      -- 도서 번호 (외래키)

	constraint primary key (lno),
    constraint foreign key(uno) references user(uno) on update cascade on delete cascade,
    constraint foreign key(bno) references book(bno) on update cascade on delete cascade
);

INSERT INTO loan (ldate, lddate, lrdate, lstate, uno, bno) VALUES
('2025-06-20', '2025-06-27', NULL, 0, 1, 3),  
('2025-06-10', '2025-06-17', '2025-06-16', 1, 2, 2),
('2025-06-25', '2025-07-02', NULL, 0, 3, 5),
('2025-06-01', '2025-06-08', '2025-06-07', 1, 4, 1),
('2025-06-15', '2025-06-22', NULL, 0, 5, 4);



