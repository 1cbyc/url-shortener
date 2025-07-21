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

  return (
    <div className="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
      <div className="bg-white rounded-xl shadow-xl p-8 w-full max-w-lg relative">
        <button onClick={onClose} className="absolute top-2 right-2 text-slate-400 hover:text-orange-500 text-2xl">&times;</button>
        <h2 className="text-2xl font-bold mb-4 text-center">Analytics for <span className="text-orange-600">{code}</span></h2>
        {loading ? (
          <div className="text-center text-slate-500">Loading...</div>
        ) : error ? (
          <div className="text-center text-red-500">{error}</div>
        ) : clicks.length === 0 ? (
          <div className="text-center text-slate-500">No clicks yet.</div>
        ) : (
          <div className="overflow-x-auto">
            <table className="min-w-full text-sm border border-slate-200 rounded-lg">
              <thead>
                <tr className="bg-slate-100">
                  <th className="px-3 py-2 text-left">Time</th>
                  <th className="px-3 py-2 text-left">IP</th>
                  <th className="px-3 py-2 text-left">Referrer</th>
                  <th className="px-3 py-2 text-left">Country</th>
                </tr>
              </thead>
              <tbody>
                {clicks.map(click => (
                  <tr key={click.id} className="border-t border-slate-200">
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