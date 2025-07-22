import { useState } from "react";
import AuthForm from "./components/AuthForm";
import AnalyticsPanel from "./components/AnalyticsPanel";
import UserDashboard from "./components/UserDashboard";
import AdminDashboard from "./components/AdminDashboard";
import NavBar from "./components/NavBar";

export default function App() {
  const [url, setUrl] = useState("");
  const [custom, setCustom] = useState("");
  const [shortUrl, setShortUrl] = useState("");
  const [feedback, setFeedback] = useState("");
  const [loading, setLoading] = useState(false);
  const [copied, setCopied] = useState(false);
  const [qr, setQr] = useState("");
  const [authOpen, setAuthOpen] = useState<false | "login" | "register">(false);
  const [authLoading, setAuthLoading] = useState(false);
  const [user, setUser] = useState<string | null>(null);
  const [analyticsOpen, setAnalyticsOpen] = useState(false);
  const [analyticsCode, setAnalyticsCode] = useState("");
  const [page, setPage] = useState<"home" | "dashboard" | "admin">("home");
  const isAdmin = user === "admin@example.com";

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setFeedback("");
    setShortUrl("");
    setQr("");
    setLoading(true);
    try {
      const form = new FormData();
      form.append("url", url);
      if (custom) form.append("custom", custom);
      const res = await fetch("/shorten", { method: "POST", body: form, credentials: "include" });
      const data = await res.json();
      if (data.short_url) {
        setShortUrl(data.short_url);
        setFeedback("Short URL created successfully!");
        setQr(`https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=${encodeURIComponent(data.short_url)}`);
      } else if (data.error) {
        setFeedback(data.error);
      } else {
        setFeedback("Unexpected error. Please try again.");
      }
    } catch {
      setFeedback("Network error. Please try again.");
    }
    setLoading(false);
  };

  const handleCopy = () => {
    if (shortUrl) {
      navigator.clipboard.writeText(shortUrl);
      setCopied(true);
      setTimeout(() => setCopied(false), 1200);
    }
  };

  const handleAuth = async (email: string, password: string) => {
    setAuthLoading(true);
    const endpoint = authOpen === "login" ? "/login" : "/register";
    const form = new FormData();
    form.append("email", email);
    form.append("password", password);
    const res = await fetch(endpoint, { method: "POST", body: form, credentials: "include" });
    const text = await res.text();
    setAuthLoading(false);
    if (res.ok && (text === "Logged in" || text === "Registered")) {
      setUser(email);
      setAuthOpen(false);
      setPage("dashboard");
    } else {
      throw new Error(text);
    }
  };

  const handleLogout = async () => {
    await fetch("/logout", { method: "POST", credentials: "include" });
    setUser(null);
    setPage("home");
  };

  const openAnalytics = (code?: string) => {
    const c = code || (shortUrl ? shortUrl.split("/").pop() || "" : "");
    if (c) {
      setAnalyticsCode(c);
      setAnalyticsOpen(true);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 to-slate-200">
      <NavBar
        user={user}
        isAdmin={isAdmin}
        onLogin={() => setAuthOpen("login")}
        onRegister={() => setAuthOpen("register")}
        onLogout={handleLogout}
        onDashboard={() => setPage("dashboard")}
        onAdmin={() => setPage("admin")}
        onHome={() => setPage("home")}
      />
      <div className="flex items-center justify-center px-2">
        <div className="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center mt-8">
          {page === "dashboard" && user ? (
            <UserDashboard user={user} onShowAnalytics={openAnalytics} />
          ) : page === "admin" && isAdmin ? (
            <AdminDashboard />
          ) : (
            <>
              <h1 className="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Shorten Your Link</h1>
              <p className="text-slate-500 mb-6 text-center">Paste your long URL below and get a short, shareable link instantly.</p>
              <form className="w-full flex flex-col gap-4" onSubmit={handleSubmit}>
                <input
                  type="url"
                  className="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-base"
                  placeholder="Paste your long URL here"
                  value={url}
                  onChange={e => setUrl(e.target.value)}
                  required
                />
                <input
                  type="text"
                  className="w-full px-4 py-3 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 text-base"
                  placeholder="Custom short code (optional)"
                  value={custom}
                  onChange={e => setCustom(e.target.value)}
                />
                <button
                  type="submit"
                  className="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg transition-colors text-lg flex items-center justify-center gap-2 disabled:opacity-60"
                  disabled={loading}
                >
                  {loading ? (
                    <svg className="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" /><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z" /></svg>
                  ) : null}
                  Shorten URL
                </button>
              </form>
              {feedback && (
                <div className={`mt-5 text-center font-semibold ${shortUrl ? "text-green-600" : "text-red-500"}`}>{feedback}</div>
              )}
              {shortUrl && (
                <div className="mt-6 w-full flex flex-col items-center gap-3 animate-fade-in">
                  <div className="flex items-center gap-2 bg-slate-100 rounded-lg px-4 py-2">
                    <a href={shortUrl} target="_blank" rel="noopener noreferrer" className="text-orange-600 font-bold text-lg hover:underline">{shortUrl}</a>
                    <button onClick={handleCopy} className="ml-2 px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white rounded transition-colors text-sm">
                      {copied ? "Copied!" : "Copy"}
                    </button>
                    <button onClick={() => openAnalytics()} className="ml-2 px-2 py-1 bg-slate-200 hover:bg-orange-100 text-orange-600 rounded transition-colors text-sm font-semibold">
                      Analytics
                    </button>
                  </div>
                  {qr && (
                    <div className="flex flex-col items-center gap-1">
                      <img src={qr} alt="QR Code" className="w-40 h-40 rounded-lg border border-slate-200" />
                      <span className="text-xs text-slate-400">Scan QR to open</span>
                    </div>
                  )}
                </div>
              )}
            </>
          )}
          {authOpen && (
            <div className="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
              <div className="bg-white rounded-xl shadow-xl p-8 w-full max-w-sm relative">
                <button onClick={() => setAuthOpen(false)} className="absolute top-2 right-2 text-slate-400 hover:text-orange-500 text-2xl">&times;</button>
                <h2 className="text-2xl font-bold mb-4 text-center">{authOpen === "login" ? "Log In" : "Register"}</h2>
                <AuthForm mode={authOpen} onAuth={handleAuth} loading={authLoading} />
              </div>
            </div>
          )}
          {analyticsOpen && (
            <AnalyticsPanel code={analyticsCode} onClose={() => setAnalyticsOpen(false)} />
          )}
        </div>
      </div>
    </div>
  );
}
