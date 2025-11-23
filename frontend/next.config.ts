/** @type {import('next').NextConfig} */
const nextConfig = {
  // 1. Replicating `base: "/reglament-system"`
  // This tells Next.js to prefix all asset paths (JS, CSS, images)
  // and all page routes with '/reglament-system'.
  basePath: "/reglament-system",

  // 2. Setting the output to 'export' for static hosting (Optional but common for fixed base paths)
  // If you are hosting on a static server (like GitHub Pages or a simple web server)
  // without a Node.js server, you should use output: 'export'.
  // If you use a custom Next.js server or Vercel, you can omit this line.
  // output: 'export',

  // 3. Optional: Configure Tailwind CSS
  // Tailwind is usually auto-configured in Next.js, but if you need a custom path
  // for your config, you'd configure it here (less common).
  // This is typically not needed unless you're using a custom PostCSS setup.

  // 4. Optional: Next.js 'resolve' equivalent for `@`
  // In Next.js, you usually configure the path alias directly in `jsconfig.json`
  // or `tsconfig.json` for **runtime** imports. For the build system, Next.js
  // automatically handles it well with the default configuration.
  // Since you use **TypeScript**, ensure this is in your `tsconfig.json`:
  /*
  "compilerOptions": {
    "paths": {
      "@/*": ["./src/*"]
    }
  }
  */

  // 5. Other typical settings:
  reactStrictMode: true,
};

module.exports = nextConfig;
