# 📚 ReadMe
**[개인 프로젝트]**
<br>
PHP와 MySQL을 활용한 웹 도서 관리 플랫폼으로, 회원·관리자 간 권한 구분을 통해 효율적인 도서관 운영을 지원합니다. 도서 검색과 대출 기능의 온라인화를 통해 사용자의 접근성을 높이고, 공지·입출고 관리 등 관리자 기능을 체계화하여 도서 운영의 디지털 전환을 실현한 웹 시스템입니다.
<br><br><br>

# 🎥 프로젝트 시연영상
https://www.youtube.com/watch?v=bWnpYYXedCI

<br><br><br>

## 👨‍🏫 프로젝트 소개
도서 대출/반납이 가능한 도서 관리 시스템을 구현했습니다.
일반 사용자는 도서 검색과 대출/반납 기능을 사용할 수 있으며,
관리자는 입출고 관리, 회원 관리, 공지 등록 등의 기능을 수행할 수 있습니다.
모든 기능은 사용자 권한에 따라 동작이 다르게 설정됩니다.

<br><br><br>



## ⏱ 개발기간
- 2025.07.01 ~ (진행 중)

<br><br><br>



## 💻 개발환경
- **Version** : PHP 8.4
- **IDE** : <img src="https://img.shields.io/badge/vscode-1e97e8?style=for-the-badge&logo=vscode&logoColor=white"/>
- **DataBase** : <img src="https://img.shields.io/badge/mysql-4479A1?style=for-the-badge&logo=mysql&logoColor=white">
- **Web Server** : <img src="https://img.shields.io/badge/apache-D22128?style=for-the-badge&logo=apache&logoColor=white"> <img src="https://img.shields.io/badge/xampp-FB7A24?style=for-the-badge&logo=xampp&logoColor=white">

<br><br><br>



## 📌 주요 기능
- **비회원**
  - 도서 목록 및 상세 정보 조회
  - 공지사항 목록 및 내용 열람
    
 <br>
 
- **회원**
  - 도서 대출 및 반납
  - 마이페이지 (내 정보, 대출/반납 내역 확인)
  - 내 정보 수정 및 회원 탈퇴
  - 도서 상세 페이지에서 대출 가능 여부 표시
     - 대출 가능, 재고 부족, 대출 중 상태에 따라 버튼 활성/비활성

<br>

- **관리자**
  - 신규 도서 등록, 수정 및 삭제
  - 도서 입출고 등록, 내역 조회, 재고 자동 반영
  - 회원 목록 확인, 회원 상태 변경 및 탈퇴 처리
  - 공지사항 등록, 수정 및 삭제
  - 공지 상세 페이지에서 관리자 로그인 시에만 수정/삭제 버튼 노출




