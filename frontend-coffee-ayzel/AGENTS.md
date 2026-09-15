# AGENTS.md

## Project

React + Vite + Tailwind CSS v4 landing page ("Coffee Ayzel"). No TypeScript, no test framework, single package. Content is in Indonesian.

## Commands

- `npm install` — install deps
- `npm run dev` — start Vite dev server (HMR on `localhost:5173`)
- `npm run build` — production build to `dist/`
- `npm run lint` — run ESLint (flat config)
- `npm run preview` — preview production build locally

## Structure

- `src/main.jsx` — app entrypoint (renders `<App />` into `#root` with `StrictMode`)
- `src/App.jsx` — root component with `BrowserRouter`, `ScrollToTop`, and 5 routes (`/`, `/about`, `/products`, `/testimonials`, `/contact`)
- `src/components/` — 7 page components: `Navbar`, `Home`, `About`, `Products`, `Testimonials`, `Footer`, `Contact`
- `src/index.css` — Tailwind v4 import (`@import "tailwindcss"`) + custom `@layer utilities` with animations (`animate-fade-up`, `animate-fade-left`, `animate-fade-right`, `animate-slide-up`, `animate-bounce-in`, `animate-bounce-slow`)
- `src/App.css` — legacy Vite template CSS, unused
- `src/assets/` — images imported directly in components (`hero.png`, `bg.png`, `vite.svg`, `react.svg`)
- `public/` — static assets (`favicon.svg`, `icons.svg`)

## Gotchas

- Build output goes to `dist/` — gitignored, safe to delete
- ESLint only lints `**/*.{js,jsx}` files; `.css` files are not linted
- Tailwind v4 (not v3): uses `@import "tailwindcss"` in CSS, not `@tailwind` directives. Configured via `@tailwindcss/vite` plugin in `vite.config.js`
- Tailwind classes work directly in JSX — no CSS module imports needed
- React Router v7 from `react-router-dom` — uses `<Routes>`, `<Route>`, `useLocation`, `<Link>` (not `<a>` for SPA navigation)
- Contact form opens WhatsApp via `wa.me` URL (`window.open`), no backend — `WA_NUMBER` constant in `Contact.jsx`
- `src/App.css` is legacy template CSS and not imported anywhere; don't add styles there
- No TypeScript in source despite `@types/react` in devDeps — all files are `.jsx`/`.js`
- No tests exist; no test command to run
- No CI workflows or pre-commit hooks present
- Vite config registers both `react()` and `tailwindcss()` plugins — both needed for dev/build
