# Kakao REST API PHP 예제

이 프로젝트는 카카오 REST API를 PHP로 구현한 예제입니다. Node.js 버전을 PHP로 포팅한 것으로, 카카오 로그인 및 다양한 API 기능을 테스트해볼 수 있습니다.

## 기능

- 카카오 로그인 (REST API)
- 사용자 프로필 조회
- 친구 목록 조회
- 나에게 메시지 보내기
- 친구에게 메시지 보내기
- 로그아웃
- 연결 끊기

## 시작하기

1. PHP가 설치되어 있어야 합니다 (PHP 7.4 이상 권장).
2. 프로젝트를 클론합니다:
   ```bash
   git clone [repository-url]
   cd [project-directory]
   ```

3. 내장 PHP 서버를 실행합니다:
   ```bash
   php -S localhost:4000
   ```

4. 웹 브라우저에서 다음 주소로 접속합니다:
   ```
   http://localhost:4000
   ```

## 환경 설정

1. [Kakao Developers](https://developers.kakao.com)에서 애플리케이션을 생성합니다.
2. 생성된 애플리케이션의 REST API 키를 `api.php` 파일의 `$client_id` 변수에 설정합니다.
3. 플랫폼 설정에서 다음 Redirect URI를 추가합니다:
   ```
   http://localhost:4000/api.php?action=redirect
   ```

## 주의사항

- 이 예제는 학습 목적으로 제작되었습니다.
- 실제 서비스에서는 보안을 강화하고 프론트엔드와 백엔드를 분리하여 구현하는 것을 권장합니다.
- API 키와 같은 중요한 정보는 환경 변수로 관리하는 것이 좋습니다.

## 스크린샷
<img width="834" alt="image" src="https://github.com/user-attachments/assets/430dd6a1-234b-499c-8513-a0a8b144a8fc" />
