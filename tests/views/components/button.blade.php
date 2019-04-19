{{--

---
slots:
    - default: My Button
variables:
    - type: primary
version: 1.0.0
status: draft
---

Lorem ipsum dolor sit amet, consectetur adipisicing elit,
sed do eiusmod tempor incididunt ut labore

--}}
<button class="btn btn-{{ $type ?? 'default' }}">
    {{ $slot }}
</button>
