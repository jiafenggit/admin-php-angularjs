(function() {
  'use strict';

  angular.module('Admin.theme')
    .directive('routerTree', routerTree);

  function routerTree($filter, Util, MeResource) {
    return {
      restrict: 'E',
      scope: {
        'option': '='
      },
      link: function link(scope, el, attr) {
        var conf, i, root, tree, diagonal, svg;
        i = 0;
        conf = {
          width: 800,
          margin: [85, 40, 85, 40],
          height: 300,
          duration: 1000,
        };
        conf = angular.extend(scope.option, conf);
        root = formatRoot(conf.data);
        root.x0 = 0;
        root.y0 = 0;
        init(root, conf);
        // root.children.forEach(collapse);

        function init(data, conf) {
          tree = d3.layout.tree()
            .size([
              conf.height - conf.margin[1] - conf.margin[3],
              conf.width - conf.margin[0] - conf.margin[2],
            ]); //创建树和设置大小
          diagonal = d3.svg.diagonal().projection(function(d) {
            return [d.y, d.x];
          });
          svg = d3.select(el[0])
            .append("svg")
            .attr("width", conf.width)
            .attr("height", conf.height) //添加一个svg元素并设置大小
            .append("g")
            .attr("transform", "translate(" + conf.margin[0] + "," + conf.margin[3] + ")");
          update(data);
        }

        function collapse(d) {
          if (d.children) {
            d._children = d.children;
            d._children.forEach(collapse);
            d.children = null;
          }
        }

        function disabled(d, status) {
          d.disabled = status;
          if (d.disabled) {
            if (d.children) {
              angular.forEach(d.children, function(v) {
                disabled(v, status);
              })
            }
            if (d._children) {
              angular.forEach(d._children, function(v) {
                disabled(v, status);
              })
            }
          } else {
            if (d.parent && typeof(d.level) === "number") {
              disabled(d.parent, status);
            }
          }

        }

        function click(d) {
          if (conf.str) {
            if (d.children) {
              d._children = d.children;
              d.children = null;
            } else {
              d.children = d._children;
              d._children = null;
            }
          } else {
            if (typeof(d.level) === 'undefined') return
            disabled(d, !d.disabled);
            scope.$apply(function() {
              scope.option.data = formatData(root);
            });
          }
          update(d);
        }

        function update(source) {
          // Compute the new tree layout.
          var nodes = tree.nodes(root).reverse(),
            links = tree.links(nodes);
          // Normalize for fixed-depth.
          nodes.forEach(function(d) {
            d.y = d.depth * 180;
          });

          // Update the nodesâ€¦
          var node = svg.selectAll("g.node")
            .data(nodes, function(d) {
              return d.id || (d.id = ++i);
            });
          // Enter any new nodes at the parent's previous position.
          var nodeEnter = node.enter().append("g")
            .attr("class", "node")
            .attr("transform", function(d) {
              return "translate(" + source.y0 + "," + source.x0 + ")";
            })
            .on("click", click); // .on("click", click);

          nodeEnter.append("circle")
            .attr("r", 1e-6)
            .attr("class", function(d) {
              if (d._children) {
                return "clp" + (d.disabled ? ' disabled' : '');
              } else {
                return d.disabled ? 'disabled' : '';
              }
            })

          nodeEnter.append("text")
            .attr("x", function(d) {
              return d.children || d._children ? -10 : 10;
            })
            .attr("dy", ".35em")
            .attr("text-anchor", function(d) {
              return d.children || d._children ? "end" : "start";
            })
            .text(function(d) {
              return d.title;
            })
            .style("fill-opacity", 1e-6);

          // Transition nodes to their new position.
          var nodeUpdate = node.transition()
            .duration(conf.duration)
            .attr("transform", function(d) {
              return "translate(" + d.y + "," + d.x + ")";
            });

          nodeUpdate.select("circle")
            .attr("r", 4.5)
            .attr("class", function(d) {
              if (d._children) {
                return "clp" + (d.disabled ? ' disabled' : '');
              } else {
                return d.disabled ? 'disabled' : '';
              }
            })

          nodeUpdate.select("text")
            .style("fill-opacity", 1);

          // Transition exiting nodes to the parent's new position.
          var nodeExit = node.exit().transition()
            .duration(conf.duration)
            .attr("transform", function(d) {
              return "translate(" + source.y + "," + source.x + ")";
            })
            .remove();

          nodeExit.select("circle")
            .attr("r", 1e-6);

          nodeExit.select("text")
            .style("fill-opacity", 1e-6);

          // Update the linksâ€¦
          var link = svg.selectAll("path.link")
            .data(links, function(d) {
              return d.target.id;
            });
          // Enter any new links at the parent's previous position.
          link.enter().insert("path", "g")
            .attr("class", function(d) {
              if (d.target.disabled) {
                return "link disabled";
              }
              return "link";
            })
            .attr("d", function(d) {
              var o = {
                x: source.x0,
                y: source.y0
              };
              return diagonal({
                source: o,
                target: o
              });
            });

          // Transition links to their new position.
          link.transition()
            .duration(conf.duration)
            .attr("d", diagonal)
            .attr("class", function(d) {
              if (d.target.disabled) {
                return "link disabled";
              }
              return "link";
            });

          // Transition exiting nodes to the parent's new position.
          link.exit().transition()
            .duration(conf.duration)
            .attr("d", function(d) {
              var o = {
                x: source.x,
                y: source.y
              };
              return diagonal({
                source: o,
                target: o
              });
            })
            .remove();

          // Stash the old positions for transition.
          nodes.forEach(function(d) {
            d.x0 = d.x;
            d.y0 = d.y;
          });
        }

        function formatRoot(data) {
          var d, def, res, selected;
          d = angular.copy(MeResource.defaults.router);
          console
          if (data === '*') {
            def = d.map(function(s) {
              s.disabled = false;
            });
          } else {
            def = Util.formatRouterObj(angular.fromJson(data));
            d.map(function(s) {
              selected = $filter('filter')(def, {
                name: s.name,
                parent: s.parent,
                level: s.level
              });
              s.disabled = !(selected.length > 0);
            });
          };
          res = {
            "title": "后台管理系统",
            "name": "root"
          };
          selected = $filter('filter')(d, {
            parent: "root",
            level: 0
          });
          if (selected.length > 0) {
            res.children = _formatItem(selected, 0, d);
          };
          return res;
          console.log(res);
        }

        function _formatItem(items, level, d) {
          var selected, item, res = [];
          angular.forEach(items, function(v) {
            item = {
              disabled: v.disabled,
              name: v.name,
              title: v.title,
              level: v.level
            };
            selected = $filter('filter')(d, {
              parent: v.name,
              level: level + 1
            });
            if (selected.length > 0) {
              item.children = _formatItem(selected, level + 1, d);
            }
            res.push(item)
          })
          return res;
        }

        function formatData(r) {
          return angular.toJson(_decodeItem(r.children));
        }

        function _decodeItem(items) {
          var res = {};
          angular.forEach(items, function(v) {
            if (v.disabled === false) {
              if (typeof(v.children) === "undefined") {
                res[v.name] = {}
              } else {
                res[v.name] = _decodeItem(v.children);
              }
            }
            return;
          })
          return res;
        }
      }
    }
  }

})();