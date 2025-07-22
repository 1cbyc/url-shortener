import React, { useState } from "react";

type Props = {
  user: string | null;
  isAdmin: boolean;
  onLogin: () => void;
  onRegister: () => void;
  onLogout: () => void;
  onDashboard: () => void;
  onAdmin: () => void;
  onHome: () => void;
};

export default function Sidebar({ user, isAdmin, onLogin, onRegister, onLogout, onDashboard, onAdmin, onHome }: Props) {
  const [open, setOpen] = useState(false);

  // Sidebar content as a function for reuse
  const sidebarContent = (
    <>
      <div className="mb-10 flex items-center justify-center">
        <span className="font-extrabold text-orange-600 text-2xl cursor-pointer" onClick={onHome} tabIndex={0} aria-label="Go to home" role="button" onKeyDown={e => (e.key === 'Enter' || e.key === ' ') && onHome()}>Shortly</span>
      </div>
      <nav className="flex-1 flex flex-col gap-2" aria-label="Main navigation">
        <button onClick={onHome} aria-label="Home" className="text-left px-4 py-2 rounded-lg hover:bg-orange-50 font-semibold text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">Home</button>
        {user && <button onClick={onDashboard} aria-label="Dashboard" className="text-left px-4 py-2 rounded-lg hover:bg-orange-50 font-semibold text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">Dashboard</button>}
        {isAdmin && <button onClick={onAdmin} aria-label="Admin" className="text-left px-4 py-2 rounded-lg hover:bg-orange-50 font-semibold text-slate-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">Admin</button>}
      </nav>
      <div className="mt-auto flex flex-col gap-2">
        {user ? (
          <button onClick={onLogout} aria-label="Logout" className="w-full px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-bold focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">Logout</button>
        ) : (
          <>
            <button onClick={onLogin} aria-label="Login" className="w-full px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-bold focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">Login</button>
            <button onClick={onRegister} aria-label="Register" className="w-full px-4 py-2 rounded-lg bg-orange-100 hover:bg-orange-200 text-orange-700 font-bold focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">Register</button>
          </>
        )}
      </div>
    </>
  );

  return (
    <>
      {/* Topbar for mobile */}
      <div className="md:hidden w-full flex items-center justify-between px-4 py-3 bg-white border-b border-slate-200 shadow z-20">
        <span className="font-extrabold text-orange-600 text-2xl cursor-pointer" onClick={onHome} tabIndex={0} aria-label="Go to home" role="button" onKeyDown={e => (e.key === 'Enter' || e.key === ' ') && onHome()}>Shortly</span>
        <button onClick={() => setOpen(!open)} aria-label="Open menu" className="text-3xl text-orange-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">
          &#9776;
        </button>
      </div>
      {/* Sidebar for desktop, drawer for mobile */}
      <aside className={`fixed md:static top-0 left-0 h-full w-64 bg-white border-r border-slate-200 flex flex-col py-8 px-4 shadow-lg z-30 transition-transform duration-200 md:translate-x-0 ${open ? "translate-x-0" : "-translate-x-full"} md:translate-x-0 md:flex md:h-screen md:w-64`}
        style={{ minHeight: '100vh' }}
        aria-label="Sidebar navigation"
      >
        {/* Close button for mobile drawer */}
        <button
          className="md:hidden absolute top-4 right-4 text-2xl text-slate-400 hover:text-orange-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400"
          onClick={() => setOpen(false)}
          aria-label="Close menu"
        >
          &times;
        </button>
        {sidebarContent}
      </aside>
      {/* Overlay for mobile drawer */}
      {open && (
        <div className="fixed inset-0 bg-black/30 z-20 md:hidden" onClick={() => setOpen(false)} aria-label="Sidebar overlay" />
      )}
    </>
  );
} 