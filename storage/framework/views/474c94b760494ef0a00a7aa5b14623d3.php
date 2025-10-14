<!doctype html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My To Do List — Pro</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Inter font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

  <style>
    :root{
      --gradient: linear-gradient(90deg, #06d7d9 0%, #4bb5ff 35%, #7a5af8 65%, #b517ff 100%);
      --card-shadow: 0 10px 25px rgba(16, 24, 40, .04);
      --ring: 0 0 0 4px rgba(122, 90, 248, .15);
    }

    /* Base */
    html, body { height: 100%; margin: 0; }
    body{
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      color:#1f2937;
      overflow-x: hidden;
    }


    body::before{
      content:"";
      position:fixed; inset:0;
      background: url("images/hero.webp") no-repeat center center / cover;
      filter: blur(7px) brightness(0.99);
      transform: scale(1.05);            /* لمنع ظهور أطراف شفافة بعد الـblur */
      z-index:-2;
    }
    body::after{
      content:"";
      position:fixed; inset:0;
      /* طبقة تعتيم ناعمة + غسلة لونية خفيفة */
      background:
        radial-gradient(1200px 600px at 10% 10%, rgba(6,215,217,.10), transparent 60%),
        radial-gradient(1000px 600px at 90% 90%, rgba(181,23,255,.08), transparent 60%),
        rgba(15,23,42,0.30);
      z-index:-1;
    }

    /* App container (Glassmorphism) */
    .app-wrap{
      max-width: 860px;
      margin: 60px auto;
      padding: 24px 20px;
      background: rgba(255,255,255,0.75);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border-radius: 16px;
      box-shadow: 0 20px 50px rgba(0,0,0,0.15);
      border: 1px solid rgba(255,255,255,0.6);
    }
    @supports not ((backdrop-filter: blur(10px)) or (-webkit-backdrop-filter: blur(10px))){
      .app-wrap{ background: rgba(255,255,255,0.92); } /* بديل في المتصفحات القديمة */
    }

    /* Title */
    .title{font-weight: 800; letter-spacing:.5px; text-transform: uppercase; text-align:center;}
    .title span{background: var(--gradient); -webkit-background-clip: text; background-clip: text; color: transparent;}

    /* Input Row */
    .input-card{background:#fff; border:0; border-radius:14px; box-shadow: var(--card-shadow); padding: 10px; display:flex; gap:10px; align-items:center}
    .input-card input{border:0; outline:0; height:56px; padding-inline:16px; flex:1; font-size:16px}
    .input-card input::placeholder{color:#9aa4b2}
    .btn-save{height:56px; min-width:120px; border:0; border-radius:12px; padding:0 22px; color:#fff; font-weight:700; background: var(--gradient); box-shadow: 0 6px 16px rgba(122, 90, 248, .25)}
    .btn-save:focus{box-shadow: 0 6px 16px rgba(122, 90, 248, .25), var(--ring)}

    /* Stats */
    .stats{display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-top:18px}
    .stat{background:#fff; border-radius:8px; box-shadow: var(--card-shadow); border:1px solid #eef0f4}
    .stat .inner{padding:10px 16px; font-weight:600}
    .stat .label{opacity:.8}
    .stat .value{background: var(--gradient); -webkit-background-clip: text; background-clip: text; color: transparent; font-weight:800}

    /* List */
    .todo-item{background:#fff; border:1px solid #eef0f4; border-radius:10px; box-shadow: var(--card-shadow); padding:16px 16px; display:flex; align-items:center; justify-content:space-between}
    .todo-item + .todo-item{margin-top:16px}
    .todo-title{margin:0; font-weight:600; color:#374151}
    .todo-title.done{color:#9aa4b2; text-decoration: line-through}
    .todo-actions{display:flex; gap:12px}
    .icon-btn{width:34px; height:34px; display:grid; place-items:center; border-radius:8px; border:1px solid #eef0f4; background:#fff; transition: transform .08s ease}
    .icon-btn:hover{transform: translateY(-1px)}
    .icon-trash{color:#ff4d4f}
    .icon-edit{color:#ff9f2d}
    .icon-done{color:#28c76f}
    .icon-undo{color:#64748b}

    .muted{color:#94a3b8}

    @media (max-width: 576px){
      .btn-save{min-width:96px}
      .todo-title{font-size:15px}
    }
  </style>
</head>
<body>

  <main class="app-wrap">
    <!-- Title -->
    <h1 class="title display-6 mb-4"><span>MY TO DO LIST</span></h1>

    <!-- Input -->
    <div class="input-card mb-3">
      <input id="newTask" type="text" placeholder="Masukan to do list" aria-label="Add a task" />
      <button id="saveBtn" class="btn-save">Save</button>
    </div>

    <!-- Stats -->
    <div class="stats" role="status" aria-live="polite">
      <div class="stat"><div class="inner"><span class="label">Todo Done : </span><span id="doneCount" class="value">0</span></div></div>
      <div class="stat"><div class="inner"><span class="label">Todo On Progress : </span><span id="progressCount" class="value">0</span></div></div>
    </div>

    <!-- List -->
    <section class="mt-4" id="listWrap" aria-label="Tasks list">
      <!-- Items are injected here -->
    </section>


    <!-- Toasts -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
      <div id="liveToast" class="toast align-items-center text-bg-dark border-0" role="status" aria-live="polite" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body" id="toastBody">تم</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>

    <!-- Empty state -->
    <p id="emptyState" class="text-center muted mt-4 mb-0" hidden>No tasks yet — add your first one ✨</p>
  </main>

    <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDelete" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">حذف المهمة</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">هل أنت متأكد من حذف هذه المهمة؟ لا يمكن التراجع.</div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button id="confirmDeleteBtn" type="button" class="btn btn-danger">حذف</button>
          </div>
        </div>
      </div>
    </div>
<script>
  // ====== إعدادات API ======
  // إذا كانت الواجهة تُخدَّم من نفس دومين Laravel استخدم مسارًا نسبيًا: '' (فارغ) + '/api'
  // وإن كانت منفصلة، ضع الدومين: 'http://localhost:8000'
  const API_ORIGIN = ''; // مثال: '' أو 'http://localhost:8000'
  const API_BASE = `${API_ORIGIN}/api`;

  // ====== حالة التطبيق وعناصر الواجهة ======
  const state = { tasks: [], isLoading: false };

  const els = {
    input: document.getElementById('newTask'),
    save: document.getElementById('saveBtn'),
    list: document.getElementById('listWrap'),
    empty: document.getElementById('emptyState'),
    doneCount: document.getElementById('doneCount'),
    progressCount: document.getElementById('progressCount'),
    confirmDeleteBtn: document.getElementById('confirmDeleteBtn'),
    toast: document.getElementById('liveToast'),
    toastBody: document.getElementById('toastBody'),
    confirmModalEl: document.getElementById('confirmDelete'),
  };

  // Bootstrap helpers
  const getModal = () => bootstrap.Modal.getOrCreateInstance(els.confirmModalEl);
  const showToast = (msg) => {
    els.toastBody.textContent = msg;
    bootstrap.Toast.getOrCreateInstance(els.toast).show();
  };

  // ====== استدعاءات API (شكل الاستجابة = JSON مباشر من الكنترولر) ======
  async function apiGetTasks() {
    const res = await fetch(`${API_BASE}/tasks`, { headers: { 'Accept': 'application/json' } });
    if (!res.ok) throw new Error('فشل تحميل المهام');
    return await res.json(); // مصفوفة مهام
  }

  async function apiCreateTask(title) {
    const res = await fetch(`${API_BASE}/tasks`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ title })
    });
    if (!res.ok) throw new Error('فشل إنشاء المهمة');
    return await res.json(); // كائن مهمة
  }

  async function apiUpdateTask(id, patch) {
    const res = await fetch(`${API_BASE}/tasks/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(patch)
    });
    if (!res.ok) throw new Error('فشل تحديث المهمة');
    return await res.json(); // كائن مهمة بعد التحديث
  }

  async function apiDeleteTask(id) {
    const res = await fetch(`${API_BASE}/tasks/${id}`, { method: 'DELETE' });
    if (!res.ok) throw new Error('فشل حذف المهمة');
  }

  async function apiReorder(tasksPairs) {
    // متوافق مع الكنترولر: public function reorder(Request $request) expects 'tasks' => [ {id, order}, ... ]
    const res = await fetch(`${API_BASE}/tasks/reorder`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify({ tasks: tasksPairs })
    });
    if (!res.ok) throw new Error('فشل إعادة الترتيب');
    return await res.json();
  }

  // ====== واجهة المستخدم ======
  function setLoading(on) {
    state.isLoading = on;
    els.save.disabled = on;
    els.save.textContent = on ? 'Saving…' : 'Save';
  }

  async function loadTasks() {
    try {
      setLoading(true);
      const data = await apiGetTasks();
      // ترتيب آمن: حسب order، ثم id كبديل
      state.tasks = (data || []).sort((a, b) => {
        const ao = (a.order ?? 0), bo = (b.order ?? 0);
        return ao - bo || a.id - b.id;
      });
      render();
    } catch (e) {
      console.error(e);
      showToast('تعذر تحميل المهام');
    } finally {
      setLoading(false);
    }
  }

  async function addTask(title) {
    const t = (title || '').trim();
    if (!t) return;
    try {
      setLoading(true);
      const newTask = await apiCreateTask(t);
      state.tasks.push(newTask);
      renumberOrders();  // تأكد من وجود order محليًا
      els.input.value = '';
      render();
      showToast('تمت إضافة المهمة');
    } catch (e) {
      console.error(e);
      showToast('تعذر إنشاء المهمة');
    } finally {
      setLoading(false);
    }
  }

  async function toggleDone(id) {
    const task = state.tasks.find(t => t.id === id);
    if (!task) return;
    try {
      const updated = await apiUpdateTask(id, { done: !task.done });
      Object.assign(task, updated);
      render();
      showToast('تم تغيير حالة المهمة');
    } catch (e) {
      console.error(e);
      showToast('تعذر تحديث الحالة');
    }
  }

  async function editTask(id) {
    const task = state.tasks.find(t => t.id === id);
    if (!task) return;
    const next = prompt('Edit task', task.title);
    if (next === null) return; // إلغاء
    const val = next.trim();
    if (!val) return alert('Title cannot be empty');
    try {
      const updated = await apiUpdateTask(id, { title: val });
      Object.assign(task, updated);
      render();
      showToast('تم تحديث المهمة');
    } catch (e) {
      console.error(e);
      showToast('تعذر التحديث');
    }
  }

let pendingDeleteId = null;

function askDelete(id) {
  pendingDeleteId = id;
  bootstrap.Modal.getOrCreateInstance(els.confirmModalEl).show();
}

async function actuallyDelete(id) {
  await apiDeleteTask(id);                      // يرسل DELETE /api/tasks/{id}
  state.tasks = state.tasks.filter(t => t.id !== id);  // يحذف محليًا
  state.tasks.forEach((t, idx) => t.order = idx);      // أعِد ترقيم order (اختياري)
  render();
  showToast('تم حذف المهمة');
}


  // عدّادات
  function updateCounters() {
    const done = state.tasks.filter(t => t.done).length;
    const progress = state.tasks.length - done;
    els.doneCount.textContent = done;
    els.progressCount.textContent = progress;
  }

  // ====== الرسم ======
  function render() {
    els.list.innerHTML = '';
    if (state.tasks.length === 0) {
      els.empty.hidden = false;
      updateCounters();
      return;
    }
    els.empty.hidden = true;

    state.tasks.forEach(t => {
      const item = document.createElement('div');
      item.className = 'todo-item';
      item.setAttribute('draggable', 'true');
      item.dataset.id = t.id;

      const title = document.createElement('p');
      title.className = 'todo-title' + (t.done ? ' done' : '');
      title.textContent = t.title;
      title.title = 'Double-click to edit';
      title.addEventListener('dblclick', () => editTask(t.id));

      const actions = document.createElement('div');
      actions.className = 'todo-actions';

      const del = document.createElement('button');
      del.className = 'icon-btn';
      del.title = 'Delete';
      del.innerHTML = '<i class="bi bi-trash3 icon-trash"></i>';
      del.addEventListener('click', () => askDelete(t.id));

      const edt = document.createElement('button');
      edt.className = 'icon-btn';
      edt.title = 'Edit';
      edt.innerHTML = '<i class="bi bi-pencil-square icon-edit"></i>';
      edt.addEventListener('click', () => editTask(t.id));

      const chk = document.createElement('button');
      chk.className = 'icon-btn';
      chk.title = t.done ? 'Mark as In Progress' : 'Mark as Done';
      chk.innerHTML = t.done
        ? '<i class="bi bi-arrow-counterclockwise icon-undo"></i>'
        : '<i class="bi bi-check2-circle icon-done"></i>';
      chk.addEventListener('click', () => toggleDone(t.id));

      actions.append(del, edt, chk);
      item.append(title, actions);
      els.list.append(item);

      // سحب وإفلات
      item.addEventListener('dragstart', onDragStart);
      item.addEventListener('dragover', onDragOver);
      item.addEventListener('drop', onDrop);
    });

    updateCounters();
  }

  // ====== سحب وإفلات لإعادة الترتيب ======
  let dragSrcId = null;
  function onDragStart(e) {
    dragSrcId = this.dataset.id;
    e.dataTransfer.effectAllowed = 'move';
  }
  function onDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
  }
  async function onDrop(e) {
    e.preventDefault();
    const targetId = this.dataset.id;
    if (!dragSrcId || dragSrcId === targetId) return;

    const from = state.tasks.findIndex(t => String(t.id) === String(dragSrcId));
    const to   = state.tasks.findIndex(t => String(t.id) === String(targetId));
    const [moved] = state.tasks.splice(from, 1);
    state.tasks.splice(to, 0, moved);

    renumberOrders();
    render();

    try {
      await apiReorder(state.tasks.map(t => ({ id: t.id, order: t.order ?? 0 })));
      showToast('تم حفظ الترتيب');
    } catch (e) {
      console.error(e);
      showToast('تعذر حفظ الترتيب — سيتم إعادة التحميل');
      loadTasks();
    } finally {
      dragSrcId = null;
    }
  }

  function renumberOrders() {
    state.tasks.forEach((t, idx) => { t.order = idx; });
  }

  els.save.addEventListener('click', () => addTask(els.input.value));
  els.input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') addTask(els.input.value);
  });

  els.confirmDeleteBtn.addEventListener('click', () => {
  if (!pendingDeleteId) return;
  actuallyDelete(pendingDeleteId).finally(() => {
    bootstrap.Modal.getOrCreateInstance(els.confirmModalEl).hide();
    pendingDeleteId = null;
  });
});

  loadTasks();
</script>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH /home/ubuntu/Desktop/training-exam/to_do_list/resources/views/index.blade.php ENDPATH**/ ?>