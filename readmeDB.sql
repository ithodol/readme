drop database if exists readme;
create database readme;
use readme;

# 사용자
create table user( 
	uno int unsigned auto_increment, 
    uid varchar(20) NOT NULL UNIQUE, 
    upwd varchar(30) NOT NULL, 
    uname varchar(20) NOT NULL, 
	uphone varchar(13) NOT NULL, 
    ustate boolean default 0, 
    udelete boolean default 0,
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
('admin1', '1234', '관리자1', '010-1111-9999'),
('admin2', '1234', '관리자2', '010-2222-5555'),
('admin3', '1234', '관리자3', '010-4444-6666');

# 위치
CREATE TABLE slot (
    sno INT unsigned AUTO_INCREMENT,  
    srow VARCHAR(2) NOT NULL,    
    scol INT NOT NULL,             
    constraint primary key (sno)
);

INSERT INTO slot (srow, scol) VALUES
('A', 1),
('A', 2),
('B', 1),
('B', 2);

# 도서 카테고리
CREATE TABLE category(
	cno int unsigned auto_increment,
    cname varchar(10),
    constraint primary key (cno)
);

INSERT INTO category(cname) VALUES
('소설'),('시/에세이'), ('인문'), ('경제'), ('자기계발');

# 도서
CREATE TABLE book (
    bno INT unsigned AUTO_INCREMENT,     
    btitle VARCHAR(100) NOT NULL,           
    briter VARCHAR(50) NOT NULL,             
    bpub VARCHAR(50) NOT NULL,               
    bimg VARCHAR(100),                        
    sno INT unsigned NOT NULL,                         
    cno INT unsigned NOT NULL,
    constraint primary key (bno),
    constraint foreign key(sno) references slot(sno) on update cascade on delete cascade,
    constraint foreign key(cno) references category(cno) on update cascade on delete cascade
);

INSERT INTO book (btitle, briter, bpub, bimg, sno, cno) VALUES
('파과', '구병모', '창비', 'pagua.jpg', 1, 1),
('종의 기원', '정유정', '은행나무', 'origin.jpg', 2, 1),
('완전한 행복', '정유정', '은행나무', 'happiness.jpg', 3, 1),
('살육에 이르는 병', '아비코 다케마루', '시공사', 'murder.jpg', 4, 1),
('도롱뇽의 49재', '아사히나 아키', '시공사', 'salamander.jpg', 1, 1),
('부디 당신이 타락하기를', '무경', '나비클럽', 'fall.jpg', 2, 1),
('혼모노', '성해나', '창비', 'Honmono.jpg', 3, 1),
('스토너', '존 윌리엄스', '알에이치코리아', 'stoner.jpg', 4, 1),
('1984', '조지 오웰', '민음사', '1984.jpg', 1, 1),
('데미안', '헤르만 헤세', '민음사', 'demian.jpg', 2, 1),
('첫 여름, 완주', '김금희', '무제', 'summer.jpg', 1, 1),

('우리의 낙원에서 만나자', '하태완', '북로망스', 'paradise.jpg', 1, 2),
('단 한 번의 삶', '김영하', '복복서가', 'once.jpg', 2, 2),
('어른의 행복은 조용하다', '태수', '페이지2북스', 'quiet_happiness.jpg', 3, 2),
('서랍에 저녁을 넣어 두었다', '한강', '문학과지성사', 'drawer_evening.jpg', 4, 2),
('죽음의 수용소에서', '빅터 프랭클', '청아출판사', 'mans_search.jpg', 1, 2),
('초록의 어두운 부분', '조용미', '문학과지성사', 'green_dark.jpg', 2, 2),

('초역 부처의 말', '코이케 류노스케', '포레스트북스', 'buddha.jpg', 3, 3),
('자유론', '존스튜어트 밀', '책세상', 'liberty.jpg', 4, 3),
('부의 심리학', '김경일', '포레스트북스', 'wealth_psych.jpg', 1, 3),
('도둑맞은 집중력', '요한 하리', '어크로스', 'focus.jpg', 2, 3),
('비폭력대화', '마셜 B. 로젠버그', '한국NVC출판사', 'nvc.jpg', 3, 3),

('돈의 심리학', '모건 하우절', '인플루엔셜', 'money_psych.jpg', 4, 4),
('빅 사이클', '레이 달리오', '한빛비즈', 'big_cycle.jpg', 1, 4),
('돈의 속성', '김승호', '스노우폭스북스', 'money_nature.jpg', 2, 4),
('부자 아빠 가난한 아빠', '로버트 기요사키', '민음인', 'rich_dad.jpg', 3, 4),

('행동은 불안을 이긴다', '롭 다이얼', '서삼독', 'action_beats_anxiety.jpg', 4, 5),
('데일 카네기 인간관계론', '데일 카네기', '현대지성', 'carnegie.jpg', 1, 5),
('벤저민 프랭클린 자서전', '벤저민 프랭클린', '현대지성', 'franklin.jpg', 2, 5),
('생각의 연금술', '제임스 알렌 외', '포레스트북스', 'alchemy.jpg', 3, 5),
('하루 5분 아침 일기', '인텔리전트 체인지', '심야책방', '5min_diary.jpg', 4, 5);






# 대출
CREATE TABLE loan (
    lno INT unsigned AUTO_INCREMENT,    
    ldate DATE NOT NULL,                    
    lddate DATE NOT NULL,                   
    lrdate DATE,                           
    lstate BOOLEAN NOT NULL DEFAULT 0,     
    uno INT unsigned NOT NULL,                     
    bno INT unsigned NOT NULL,                      

	constraint primary key (lno),
    constraint foreign key(uno) references user(uno) on update cascade on delete cascade,
    constraint foreign key(bno) references book(bno) on update cascade on delete cascade
);

-- INSERT INTO loan (ldate, lddate, lrdate, lstate, uno, bno) VALUES
-- ('2025-06-20', '2025-06-27', NULL, 0, 1, 3),  
-- ('2025-06-10', '2025-06-17', '2025-06-16', 1, 2, 2),
-- ('2025-06-25', '2025-07-02', NULL, 0, 3, 5),
-- ('2025-06-01', '2025-06-08', '2025-06-07', 1, 4, 1),
-- ('2025-06-15', '2025-06-22', NULL, 0, 5, 4);



# 입고 출고 재고
CREATE TABLE inven (
    ino INT UNSIGNED AUTO_INCREMENT,
    itype BOOLEAN NOT NULL, -- 0입고 1출고
    icount INT NOT NULL,
    istock INT NOT NULL,
    idate DATETIME DEFAULT CURRENT_TIMESTAMP,
    imemo VARCHAR(255),
    bno INT UNSIGNED,
    adno INT UNSIGNED,
    constraint primary key (ino),
	constraint foreign key(bno) references book(bno) on update cascade on delete cascade,
    constraint foreign key(adno) references admin(adno) on update cascade on delete cascade
);

INSERT INTO inven (itype, icount, istock, imemo, bno, adno) VALUES
(0, 10, 10, '초기 입고', 1, 1),
(1, 2, 8, '도서관 손상으로 출고', 1, 1),

(0, 7, 7, '1차 입고', 4, 1),
(0, 5, 12, '2차 입고', 4, 2),

(0, 15, 15, '재고 확보', 8, 2),
(1, 5, 10, '폐기 처리', 8, 2),

(0, 12, 12, '신간 입고', 11, 1),
(1, 3, 9, '도난으로 출고', 11, 1),
(0, 6, 15, '보충 입고', 11, 1),

(0, 20, 20, '기본 입고', 15, 3),

(0, 30, 30, '다량 입고', 20, 2),
(1, 10, 20, '이관 출고', 20, 2),

(0, 5, 5, '입고 테스트', 27, 1),

(0, 8, 8, '초도 입고', 31, 3),
(1, 2, 6, '불량', 31, 3),
(1, 1, 5, '이동출고', 31, 3);



# 공지사항 게시판
CREATE TABLE notice (
    nno INT UNSIGNED AUTO_INCREMENT,
    ntit VARCHAR(30) NOT NULL,
    ncontent VARCHAR(200) NOT NULL,
    nview INT NOT NULL DEFAULT 0,
    nimg VARCHAR(255),
    adno INT UNSIGNED NOT NULL,
    ndate DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT PRIMARY KEY (nno),
    constraint foreign key(adno) references admin(adno) on update cascade on delete cascade
);

INSERT INTO notice (ntit, ncontent, nview, nimg, adno) VALUES 
('시스템 점검 안내', '7월 10일(수) 새벽 2시부터 오전 4시까지 시스템 점검이 예정되어 있습니다. 점검 시간 동안 홈페이지 접속 및 도서 검색, 대출/반납 서비스 이용이 불가하오니 이용에 참고 부탁드립니다.', 0, NULL, 1),
('신간 도서 입고 안내', '7월 한 달간 새롭게 입고된 신간 도서 목록이 등록되었습니다. 다양한 장르의 최신 도서를 구비하였으니 많은 관심과 이용 부탁드립니다. 신착 도서는 홈 화면에서 확인하실 수 있습니다.', 0, NULL, 2),
('홈페이지 개편', '이용자 편의성을 높이기 위해 홈페이지 UI가 새롭게 개편되었습니다. 보다 간편해진 검색 기능과 모바일 최적화를 통해 언제 어디서든 도서 서비스를 편리하게 이용하실 수 있습니다.', 0, NULL, 1),
('여름 휴가 일정', '도서관 여름 휴가 일정은 8월 1일(목)부터 8월 5일(월)까지입니다. 이 기간에는 도서 대출/반납 및 회원가입 등의 서비스가 제한되오니, 사전 이용을 부탁드립니다. 휴가 이후에는 정상 운영됩니다.', 0, NULL, 2),
('이벤트 안내', '도서 대출 이벤트가 진행 중입니다! 기간 내 도서를 대출하신 분들께 추첨을 통해 다양한 선물을 드립니다. 참여 방법 및 자세한 내용은 아래 이미지를 참고해주세요. 많은 참여 바랍니다!', 0, NULL, 1);

