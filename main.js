let selectedId = null;

async function getPosts() {
  let res = await fetch(`http://localhost/RESTful_API/php/posts`);
  let posts = await res.json();

  document.querySelector('.post-list').innerHTML = '';

  posts.forEach((post) => {
    document.querySelector('.post-list').innerHTML += `
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="cadr-title">${post.title}</h5>
          <div class="card-text">${post.content}</div>
          <a href="#" class="card-link">Подробнее</a>
          <a href="#" class="card-link" onclick="removePost(${post.id})">Удалить</a>
          <a href="#" class="card-link" onclick="selectPost('${post.id}', '${post.title}', '${post.content}')">Редактировать</a>
        </div>
      </div>
      `
  });
}

async function addPost() {
  const title = document.getElementById('title').value,
  content = document.getElementById('content').value;
  
  let myForm = new FormData();
  myForm.append('title', title);
  myForm.append('content', content);
  
  const res = await fetch(`http://localhost/RESTful_API/php/posts`, {
    method: 'POST',
    body: myForm
  });

  const data = await res.json();
}

async function removePost(id) {
  const res = await fetch(`http://localhost/RESTful_API/php/posts/${id}`, {
    method: "DELETE"
  });

  const data = await res.json();
}

function selectPost(id, title, content) {
  selectedId = id;
  document.getElementById('title-edit').value = title;
  document.getElementById('content-edit').value = content;
}

async function updatePost(id) {
  const title = document.getElementById('title-edit').value;
  const content = document.getElementById('content-edit').value;

  const data = {
    title: title,
    content: content
  } 

  const res = await fetch(`http://localhost/RESTful_API/php/posts/${selectedId}`, {
    method: 'PATCH',
    body: JSON.stringify(data)
  });

  let resData = res.json();

  // if (resData === true) {
  //   await getPosts();
  // }
}

getPosts();
