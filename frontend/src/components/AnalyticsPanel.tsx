import { useEffect, useState } from "react";

type Click = {
  id: number;
  referrer: string | null;
  ip: string;
  country: string | null;
  created_at: string;
};

type Props = {
  code: string;
  onClose: () => void;
};

export default function AnalyticsPanel({ code, onClose }: Props) {
  const [clicks, setClicks] = useState<Click[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");

  useEffect(() => {
    setLoading(true);
    fetch(`/api/analytics/${code}`)
      .then(res => res.json())
      .then(data => {
        setClicks(data.clicks || []);
        setLoading(false);
      })
      .catch(() => {
        setError("Failed to load analytics");
        setLoading(false);
      });
  }, [code]);

  // Find top country
  const topCountry = (() => {
    if (!clicks.length) return null;
    const counts: Record<string, number> = {};
    for (const c of clicks) {
      if (c.country) counts[c.country] = (counts[c.country] || 0) + 1;
    }
    let max = 0, top = null;
    for (const [country, count] of Object.entries(counts)) {
      if (count > max) {
        max = count;
        top = country;
      }
    }
    return top;
  })();

  return (
    <div className="w-full flex flex-col gap-4 md:gap-6 mt-4 md:mt-8">
      {/* Summary Card */}
      <div className="w-full bg-orange-50 border border-orange-200 rounded-2xl shadow p-4 sm:p-6 flex flex-col items-center relative mb-2">
        <button onClick={onClose} aria-label="Close analytics panel" title="Close analytics panel" className="absolute top-3 right-4 text-slate-400 hover:text-orange-500 text-2xl font-bold focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-400">&times;</button>
        <div className="text-2xl md:text-3xl font-extrabold text-orange-600">{clicks.length}</div>
        <div className="text-slate-700 font-semibold text-sm md:text-base">Total Clicks</div>
        {topCountry && <div className="mt-2 text-orange-700 text-xs md:text-sm font-semibold">Top Country: {topCountry}</div>}
      </div>
      <div className="w-full bg-white border border-slate-200 rounded-2xl shadow p-2 sm:p-4">
        <h2 className="text-lg md:text-xl font-bold mb-2 md:mb-4 text-slate-900">Analytics for <span className="text-orange-600">{code}</span></h2>
        {loading ? (
          <div className="text-center text-slate-500">Loading...</div>
        ) : error ? (
          <div className="text-center text-red-500">{error}</div>
        ) : clicks.length === 0 ? (
          <div className="text-center text-slate-500">No clicks yet.</div>
        ) : (
          <div className="overflow-x-auto">
            <table className="min-w-full text-xs md:text-sm border border-slate-200 rounded-lg">
              <thead>
                <tr className="bg-slate-100">
                  <th className="px-3 py-2 text-left">Time</th>
                  <th className="px-3 py-2 text-left">IP</th>
                  <th className="px-3 py-2 text-left">Referrer</th>
                  <th className="px-3 py-2 text-left">Country</th>
                </tr>
              </thead>
              <tbody>
                {clicks.map((click, idx) => (
                  <tr key={click.id} className={(idx % 2 === 0 ? "bg-white" : "bg-slate-50") + " border-t border-slate-200 group hover:bg-orange-50 transition-colors duration-150"}>
                    <td className="px-3 py-2 whitespace-nowrap">{new Date(click.created_at).toLocaleString()}</td>
                    <td className="px-3 py-2">{click.ip}</td>
                    <td className="px-3 py-2">{click.referrer || "-"}</td>
                    <td className="px-3 py-2">{click.country || "-"}</td>
                  </tr>
                ))}
              </tbody>
            </table>
            <div className="mt-3 text-slate-500 text-xs text-center">Total clicks: {clicks.length}</div>
          </div>
        )}
      </div>
    </div>
  );
} 